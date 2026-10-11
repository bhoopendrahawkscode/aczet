<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Lead;

class QuoteCartController extends Controller
{
    public function index()
    {
        $cart = session('aczet_quote_cart', []);
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get();

        return view('frontend.quote_cart', compact('cart', 'products'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $qty = (int)$request->input('quantity', 1);

        $cart = session('aczet_quote_cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId] += $qty;
        } else {
            $cart[$productId] = $qty;
        }

        session(['aczet_quote_cart' => $cart]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.cart_added'),
                'count' => count($cart)
            ]);
        }

        return redirect()->route('quote_cart.index')->with('success', __('messages.cart_added'));
    }

    public function remove(Request $request, $id)
    {
        $cart = session('aczet_quote_cart', []);
        unset($cart[$id]);
        session(['aczet_quote_cart' => $cart]);

        return back()->with('success', __('messages.cart_removed'));
    }

    public function submitConsolidatedQuote(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'company' => 'nullable|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:191',
            'region' => 'nullable|string|max:191',
            'message' => 'nullable|string',
        ]);

        $cart = session('aczet_quote_cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => __('messages.cart_empty')]);
        }

        $products = Product::whereIn('id', array_keys($cart))->get();
        $itemsSummary = [];
        foreach ($products as $p) {
            $qty = $cart[$p->id] ?? 1;
            $itemsSummary[] = "{$p->name} ({$p->model_number}) x Qty: {$qty}";
        }

        $consolidatedMessage = "Multi-Product Quote Request:\n" . implode("\n", $itemsSummary) . "\n\nClient Note: " . ($validated['message'] ?? 'None');

        $lead = Lead::create([
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'product_id' => $products->first() ? $products->first()->id : null,
            'region' => $validated['region'] ?? session('aczet_region', 'Global'),
            'source' => 'Consolidated Quote Cart',
            'status' => 'New',
            'message' => $consolidatedMessage,
            'raw_meta' => [
                'items' => $itemsSummary,
                'ip' => $request->ip(),
                'submitted_at' => now()->toDateTimeString(),
            ]
        ]);

        // Queue Consolidated RFQ Email
        try {
            \Illuminate\Support\Facades\Mail::to($lead->email)->queue(new \App\Mail\QuoteRfqMail($lead, $products));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Quote RFQ mail queue error: " . $e->getMessage());
        }

        // Clear cart
        session()->forget('aczet_quote_cart');

        return redirect()->route('home')->with('success', __('messages.quote_success'));
    }
}
