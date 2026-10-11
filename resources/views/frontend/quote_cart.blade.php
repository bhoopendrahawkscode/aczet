@extends('layouts.app')

@section('title', ($t['nav_rfq'] ?? 'Request for Quote (RFQ) Cart') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        <i class="fa-solid fa-cart-flatbed" style="color:var(--accent); margin-right:8px;"></i>{{ $t['nav_rfq'] ?? 'Quote RFQ Cart' }}
    </h1>
    <p>
        {{ $t['rfq_cart_sub'] ?? 'Review selected laboratory instruments and submit a consolidated RFQ for official pricing.' }}
    </p>
</div>

<div class="container-responsive">
    @if($products->count() > 0)
        <div class="grid-cart-layout">
            <!-- Cart Items List -->
            <div style="background: #FFFFFF; border-radius: 20px; border: 1px solid var(--border-color); padding: clamp(20px, 3.5vw, 32px); box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04); width: 100%; box-sizing: border-box;">
                <h3 style="font-family:'Space Grotesk',sans-serif; font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 20px; border-bottom: 2px solid #F1F5F9; padding-bottom: 14px;">
                    {{ $t['selected_instruments'] ?? 'Selected Instruments' }} ({{ $products->count() }})
                </h3>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($products as $p)
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; gap: 14px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 14px; min-width: 220px; flex: 1;">
                                <div style="width: 68px; height: 68px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; display: flex; align-items: center; justify-content: center; padding: 6px; flex-shrink: 0;">
                                    <img src="{{ $p->main_image }}" alt="{{ $p->getLocalizedName($currentLocale) }}" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <h4 style="font-family:'Space Grotesk',sans-serif; font-size: 1rem; font-weight: 700; color: var(--primary); margin: 0 0 4px;">
                                        <a href="{{ route('products.show', $p->slug) }}" style="color:inherit; text-decoration:none;">{{ $p->getLocalizedName($currentLocale) }}</a>
                                    </h4>
                                    <span style="font-size: 0.76rem; background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; font-weight: 800; padding: 2px 8px; border-radius: 12px; display: inline-block;">
                                        {{ $p->model_number }}
                                    </span>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 14px;">
                                <span style="font-size: 0.85rem; font-weight: 700; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 8px; color: #334155;">Qty: {{ $cart[$p->id] ?? 1 }}</span>
                                
                                <form action="{{ route('quote_cart.remove', $p->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; cursor: pointer; font-size: 0.95rem; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" title="Remove from RFQ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 24px;">
                    <a href="{{ route('products.index') }}" style="font-size: 0.88rem; font-weight: 700; color: var(--accent); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-plus"></i> {{ $t['add_more_products'] ?? 'Add More Products from Catalog' }}
                    </a>
                </div>
            </div>

            <!-- Consolidated Quote Submission Form -->
            <div style="background: #FFFFFF; border-radius: 20px; border: 1px solid var(--border-color); padding: clamp(22px, 3.5vw, 32px); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06); height: fit-content; width: 100%; box-sizing: border-box;">
                <h3 style="font-family:'Space Grotesk',sans-serif; font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 6px;">
                    {{ $t['submit_rfq_title'] ?? 'Submit RFQ Details' }}
                </h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5;">
                    {{ $t['modal_quote_sub'] ?? 'Receive a formal proforma quote with technical datasheets & delivery timelines.' }}
                </p>

                <form action="{{ route('quote_cart.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="region" value="{{ $currentRegion }}">

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_name'] ?? 'Full Name' }} *</label>
                        <input type="text" name="name" required class="form-control" placeholder="e.g. Dr. Alexander Wright">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_email'] ?? 'Work Email' }} *</label>
                        <input type="email" name="email" required class="form-control" placeholder="alexander@pharma-corp.com">
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 160px), 1fr)); gap: 12px; margin-bottom: 14px;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_phone'] ?? 'Phone' }}</label>
                            <input type="text" name="phone" class="form-control" placeholder="+1 234 567 8900">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_company'] ?? 'Company' }}</label>
                            <input type="text" name="company" class="form-control" placeholder="Company / Lab Name">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_message'] ?? 'Notes / Custom Calibrations' }}</label>
                        <textarea name="message" rows="3" placeholder="Specify readability requirements, IQ/OQ validation certificates, or delivery timeline..." class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-weight: 800; border-radius: 12px; font-size: 0.92rem;">
                        <i class="fa-solid fa-paper-plane"></i> {{ $t['modal_submit'] ?? 'Submit Quote Request' }}
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Modern Empty Cart State -->
        <div style="background: #FFFFFF; padding: 60px 24px; text-align: center; border-radius: 24px; border: 1px solid var(--border-color); max-width: 580px; margin: 30px auto 60px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
            <div style="width: 76px; height: 76px; border-radius: 20px; background: #FFF5F0; color: var(--accent); display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 20px;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h2 style="font-family:'Space Grotesk',sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--primary); margin: 0 0 10px;">
                {{ $t['cart_empty_title'] ?? 'Your RFQ Quote List is Empty' }}
            </h2>
            <p style="color: var(--text-muted); font-size: 0.92rem; margin: 0 auto 26px; max-width: 440px; line-height: 1.6;">
                {{ $t['cart_empty_sub'] ?? 'Explore our catalog and click "Add to RFQ" on any instrument to request consolidated batch pricing.' }}
            </p>
            <a href="{{ route('products.index') }}" class="btn-primary" style="padding: 12px 28px; font-size: 0.92rem;">
                <i class="fa-solid fa-layer-group"></i> {{ $t['cat_view_all'] ?? 'View Full Catalog' }}
            </a>
        </div>
    @endif
</div>
@endsection
