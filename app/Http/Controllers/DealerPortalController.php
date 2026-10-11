<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\Lead;

class DealerPortalController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('dealer')->check()) {
            return redirect()->route('dealer.dashboard');
        }
        return view('frontend.dealer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $dealer = Dealer::where('email', $credentials['email'])->first();

        if (!$dealer || !Hash::check($credentials['password'], $dealer->password)) {
            return back()->withErrors(['email' => 'Invalid email credentials or password.'])->withInput();
        }

        if ($dealer->status !== 'Approved') {
            return back()->withErrors(['email' => 'Your authorized distributor application is currently under review by Channel Sales.'])->withInput();
        }

        Auth::guard('dealer')->login($dealer, $request->boolean('remember'));

        return redirect()->route('dealer.dashboard')->with('success', 'Welcome back, ' . $dealer->contact_person . '! Authorized Distributor Session Active.');
    }

    public function dashboard(Request $request)
    {
        $dealer = Auth::guard('dealer')->user();
        $discountRate = ($dealer->discount_percentage ?? 35.00) / 100;

        $query = Product::with('category')->where('is_active', true);

        // Search query filter (by model number, name, or category)
        if ($request->filled('q')) {
            $q = trim($request->query('q'));
            $query->where(function($sub) use ($q) {
                $sub->where('model_number', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhereHas('category', function($catQuery) use ($q) {
                        $catQuery->where('name', 'like', "%{$q}%");
                    });
            });
        }

        // Per page items (10, 25, 50, 100)
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Sorting
        $sort = $request->query('sort', 'model_asc');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('model_number', 'asc');
                break;
        }

        $products = $query->paginate($perPage)->withQueryString();

        // Pass all active products for the Quick Purchase Order (PO) select dropdown
        $allProducts = Product::where('is_active', true)
            ->select('id', 'model_number', 'name', 'price')
            ->orderBy('model_number', 'asc')
            ->get();

        return view('frontend.dealer.dashboard', compact('dealer', 'products', 'allProducts', 'discountRate'));
    }

    public function submitBulkOrder(Request $request)
    {
        $dealer = Auth::guard('dealer')->user();

        $validated = $request->validate([
            'po_number' => 'required|string|max:191',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $unitPrice = $product->price ?? 1250.00;
        $discountAmount = $unitPrice * (($dealer->discount_percentage ?? 35.00) / 100);
        $dealerUnitPrice = $unitPrice - $discountAmount;
        $totalWholesale = $dealerUnitPrice * $validated['quantity'];

        $lead = Lead::create([
            'name' => $dealer->contact_person,
            'company' => $dealer->company_name,
            'email' => $dealer->email,
            'phone' => $dealer->phone,
            'product_id' => $product->id,
            'region' => $dealer->country,
            'source' => 'Authorized Dealer Portal (Wholesale PO)',
            'status' => 'New',
            'message' => "PURCHASE ORDER PO# {$validated['po_number']}\nProduct: {$product->name} ({$product->model_number})\nQty: {$validated['quantity']} units\nWholesale Total: \${$totalWholesale} (Tier: {$dealer->tier})\nShip To: {$validated['shipping_address']}\nNotes: " . ($validated['notes'] ?? 'None'),
            'raw_meta' => [
                'dealer_id' => $dealer->id,
                'po_number' => $validated['po_number'],
                'discount_percentage' => $dealer->discount_percentage,
                'total_wholesale_usd' => $totalWholesale,
            ]
        ]);

        return back()->with('success', "Wholesale Purchase Order PO# {$validated['po_number']} logged successfully! Order confirmation & Proforma Invoice sent to {$dealer->email}.");
    }

    public function logout(Request $request)
    {
        Auth::guard('dealer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dealer.login')->with('success', 'You have been logged out of the Distributor Portal.');
    }
}
