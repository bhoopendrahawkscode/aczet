<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'company' => 'nullable|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:191',
            'product_id' => 'nullable|exists:products,id',
            'region' => 'nullable|string|max:191',
            'source' => 'nullable|string|max:191',
            'message' => 'nullable|string',
            'utm_source' => 'nullable|string|max:191',
            'utm_medium' => 'nullable|string|max:191',
            'utm_campaign' => 'nullable|string|max:191',
        ]);

        $region = $validated['region'] ?? session('aczet_region', 'Global');
        $source = $validated['source'] ?? 'General Inquiry';

        $lead = Lead::create([
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'product_id' => $validated['product_id'] ?? null,
            'region' => $region,
            'source' => $source,
            'status' => 'New',
            'message' => $validated['message'] ?? null,
            'raw_meta' => [
                'user_agent' => $request->header('User-Agent'),
                'ip' => $request->ip(),
                'submitted_at' => now()->toDateTimeString(),
                'utm_source' => $validated['utm_source'] ?? $request->session()->get('aczet_utm_source'),
                'utm_medium' => $validated['utm_medium'] ?? $request->session()->get('aczet_utm_medium'),
                'utm_campaign' => $validated['utm_campaign'] ?? $request->session()->get('aczet_utm_campaign'),
            ],
        ]);

        // Dispatch Asynchronous Queue Job to Lead
        try {
            Mail::to($lead->email)->queue(new \App\Mail\LeadInquiryMail($lead));
            $notificationAddress = config('mail.lead_notification_to');
            if ($notificationAddress && $notificationAddress !== $lead->email) {
                Mail::to($notificationAddress)->queue(new \App\Mail\LeadInquiryMail($lead));
            }
        } catch (\Exception $e) {
            Log::warning("Mail queue error for lead #{$lead->id}: " . $e->getMessage());
        }

        Log::info("Lead captured [#{$lead->id}]: Auto-acknowledgment queued for {$lead->email}.");

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your quote request has been received. Our sales team will contact you within 24 hours.',
                'lead_id' => $lead->id,
            ]);
        }

        return back()->with('success', 'Thank you! Your inquiry has been submitted successfully. Our sales team will get in touch shortly.');
    }

    public function exportCsv(Request $request)
    {
        $query = Lead::with('product');

        if ($request->filled('region')) {
            $query->where('region', $request->query('region'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        $csvFileName = 'aczet_leads_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Date', 'Name', 'Company', 'Email', 'Phone', 'Product', 'Region', 'Source', 'Status', 'Message']);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->name,
                    $lead->company ?? 'N/A',
                    $lead->email,
                    $lead->phone ?? 'N/A',
                    $lead->product ? $lead->product->name : 'General Inquiry',
                    $lead->region,
                    $lead->source,
                    $lead->status,
                    $lead->message
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
