@extends('layouts.app')

@section('title', 'Distributor Partner Dashboard - ' . $dealer->company_name)

@section('content')
<!-- Top Partner Header Banner -->
<div style="background: linear-gradient(135deg, #0B132B 0%, #172554 100%); color: #FFF; padding: clamp(30px, 5vw, 45px) 5% clamp(35px, 5vw, 50px);">
    <div class="container-responsive" style="margin: 0 auto; padding: 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 18px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                <span style="background: #10B981; color: #FFF; padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-circle-check"></i> VERIFIED DISTRIBUTOR
                </span>
                <span style="background: rgba(229, 86, 4, 0.2); color: #FF7A30; border: 1px solid #0369A1; padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 800;">
                    <i class="fa-solid fa-crown"></i> {{ $dealer->tier ?? 'Tier 1 Gold Partner' }}
                </span>
            </div>
            <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.6rem, 3.5vw, 2.3rem); font-weight: 800; line-height: 1.2;">
                {{ $dealer->company_name }}
            </h1>
            <p style="color: #94A3B8; font-size: 0.88rem; margin-top: 6px; line-height: 1.5;">
                <i class="fa-solid fa-user-tie"></i> Contact: <strong>{{ $dealer->contact_person }}</strong> &bull; <i class="fa-solid fa-earth-americas"></i> Territory: <strong>{{ $dealer->country }}</strong> &bull; Discount: <strong style="color:#10B981;">{{ $dealer->discount_percentage ?? 35 }}% Off MSRP</strong>
            </p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <div style="background: rgba(255,255,255,0.08); padding: 10px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.12);">
                <div style="font-size: 0.7rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Assigned OEM Director</div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #FFF; margin-top: 2px;">{{ $dealer->account_manager ?? 'Dr. Rajesh Verma' }}</div>
            </div>
            <form action="{{ route('dealer.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-secondary" style="background: rgba(239, 68, 68, 0.2); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.35); padding: 10px 16px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>

<div class="container-responsive">
    <!-- Partner Metric Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr)); gap: 18px; margin-bottom: 32px;">
        <div style="background: #FFF; border-radius: 14px; border: 1px solid var(--border-color); padding: 20px; box-shadow: var(--shadow-sm); border-left: 4px solid var(--accent);">
            <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Wholesale Pricing Tier</span>
            <div style="font-size: 1.8rem; font-weight: 800; color: var(--primary); margin: 4px 0;">{{ $dealer->discount_percentage ?? 35 }}% Off</div>
            <span style="font-size: 0.78rem; color: #10B981; font-weight: 700;"><i class="fa-solid fa-lock-open"></i> Wholesale Margin Unlocked</span>
        </div>

        <div style="background: #FFF; border-radius: 14px; border: 1px solid var(--border-color); padding: 20px; box-shadow: var(--shadow-sm); border-left: 4px solid #2563EB;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Territory Exclusivity</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin: 4px 0;">{{ $dealer->country }}</div>
            <span style="font-size: 0.78rem; color: #2563EB; font-weight: 700;"><i class="fa-solid fa-shield-halved"></i> Authorized Representative</span>
        </div>

        <div style="background: #FFF; border-radius: 14px; border: 1px solid var(--border-color); padding: 20px; box-shadow: var(--shadow-sm); border-left: 4px solid #10B981;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Direct OEM Support Line</span>
            <div style="font-size: 1.2rem; font-weight: 800; color: var(--primary); margin: 6px 0;">+91 22 2847 4800</div>
            <span style="font-size: 0.78rem; color: #64748B;">Priority Dealer Dispatch Queue</span>
        </div>
    </div>

    <div class="grid-dealer-dash">
        <!-- Wholesale Price Matrix & Margin Calculator -->
        <div style="width: 100%;">
            <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: clamp(18px, 3vw, 28px); box-shadow: var(--shadow-sm); margin-bottom: 28px; width: 100%; box-sizing: border-box;">
                <!-- Table Header & Summary Info -->
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 18px; border-bottom: 2px solid #F1F5F9; padding-bottom: 14px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-tags" style="color: var(--accent);"></i> Wholesale Price Matrix &amp; Profit Calculator
                            </h3>
                            <span style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 0.72rem; font-weight: 700; padding: 2px 9px; border-radius: 9999px;">
                                {{ $products->total() }} Models Active
                            </span>
                        </div>
                        <p style="font-size: 0.82rem; color: #64748B; margin-top: 4px; margin-bottom: 0;">
                            Confidential dealer wholesale prices with exclusive <strong style="color: #10B981;">{{ (float)($dealer->discount_percentage ?? 35) }}% wholesale margin</strong> applied.
                        </p>
                    </div>
                    <div>
                        <span style="background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-size: 0.76rem; font-weight: 700; padding: 5px 12px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-circle-check" style="color: #10B981;"></i> Tier Discount: {{ (float)($dealer->discount_percentage ?? 35) }}% Off MSRP
                        </span>
                    </div>
                </div>

                <!-- Search & Filters Toolbar -->
                <form method="GET" action="{{ route('dealer.dashboard') }}" style="margin-bottom: 18px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                    <div style="position: relative; flex: 1; min-width: 220px;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 0.85rem; pointer-events: none;"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by model or series (e.g. CY 224, MB 50, Moisture...)" 
                               class="form-control" style="padding-left: 36px; padding-right: {{ request('q') ? '34px' : '12px' }}; height: 40px; font-size: 0.86rem; border-radius: 8px; border: 1px solid #CBD5E1; width: 100%; box-sizing: border-box;">
                        @if(request('q'))
                            <a href="{{ route('dealer.dashboard', array_filter(['per_page' => request('per_page'), 'sort' => request('sort')])) }}" 
                               title="Clear search" 
                               style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; text-decoration: none; font-size: 0.85rem;">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </a>
                        @endif
                    </div>

                    <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        <select name="sort" class="form-control" style="height: 40px; font-size: 0.84rem; border-radius: 8px; border: 1px solid #CBD5E1; padding: 0 10px; width: auto;" onchange="this.form.submit()">
                            <option value="model_asc" {{ request('sort', 'model_asc') == 'model_asc' ? 'selected' : '' }}>Model (A to Z)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A to Z)</option>
                        </select>

                        <select name="per_page" class="form-control" style="height: 40px; font-size: 0.84rem; border-radius: 8px; border: 1px solid #CBD5E1; padding: 0 10px; width: auto;" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 / page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / page</option>
                        </select>

                        <button type="submit" class="btn-primary" style="height: 40px; padding: 0 16px; font-size: 0.84rem; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fa-solid fa-filter"></i> Apply
                        </button>

                        @if(request('q') || request('sort') || request('per_page'))
                            <a href="{{ route('dealer.dashboard') }}" class="btn-secondary" style="height: 40px; padding: 0 12px; font-size: 0.82rem; border-radius: 8px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; color: #475569; background: #F1F5F9; border: 1px solid #CBD5E1;">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Table Content -->
                <div class="table-responsive" style="margin-bottom: 0;">
                    <table class="table-custom" style="margin: 0; min-width: 580px;">
                        <thead>
                            <tr>
                                <th style="width: 42%;">Product / Model</th>
                                <th style="width: 17%;">Public MSRP</th>
                                <th style="width: 17%;">Dealer Price</th>
                                <th style="width: 14%;">Profit Margin</th>
                                <th style="width: 10%; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $p)
                                @php
                                    $msrp = $p->price ?? 1450.00;
                                    $dealerPrice = $msrp * (1 - $discountRate);
                                    $margin = $msrp - $dealerPrice;
                                @endphp
                                <tr style="transition: background-color 0.15s ease;">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 44px; height: 44px; min-width: 44px; border-radius: 8px; border: 1px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 2px;">
                                                <img src="{{ $p->main_image }}" alt="{{ $p->model_number }}" style="width: 100%; height: 100%; object-fit: contain;" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                                            </div>
                                            <div style="min-width: 0;">
                                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                                    <span style="font-weight: 800; color: var(--primary); font-size: 0.92rem; letter-spacing: -0.2px;">{{ $p->model_number }}</span>
                                                    @if($p->category)
                                                        <span style="background: #F1F5F9; color: #475569; font-size: 0.68rem; font-weight: 600; padding: 2px 6px; border-radius: 4px;">{{ $p->category->name }}</span>
                                                    @endif
                                                </div>
                                                <div style="font-size: 0.78rem; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;" title="{{ $p->name }}">{{ $p->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="color: #94A3B8; text-decoration: line-through; font-weight: 600; font-size: 0.86rem;">
                                            ${{ number_format($msrp, 2) }}
                                        </div>
                                        <div style="font-size: 0.7rem; color: #94A3B8;">Retail List</div>
                                    </td>
                                    <td>
                                        <div style="color: #059669; font-weight: 800; font-size: 0.98rem; letter-spacing: -0.2px;">
                                            ${{ number_format($dealerPrice, 2) }}
                                        </div>
                                        <div style="font-size: 0.7rem; color: #10B981; font-weight: 600;">{{ (float)($dealer->discount_percentage ?? 35) }}% wholesale</div>
                                    </td>
                                    <td>
                                        <span style="background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; padding: 4px 9px; border-radius: 20px; font-size: 0.74rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                            <i class="fa-solid fa-arrow-trend-up" style="color: #10B981;"></i> +${{ number_format($margin, 2) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-sm btn-primary" onclick="setPoProduct({{ $p->id }}, '{{ addslashes($p->model_number) }}', {{ $dealerPrice }})" style="padding: 6px 12px; font-size: 0.76rem; white-space: nowrap; border-radius: 7px; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;" title="Add {{ $p->model_number }} to Purchase Order">
                                            <i class="fa-solid fa-cart-plus"></i> Order PO
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 48px 20px;">
                                        <div style="width: 52px; height: 52px; border-radius: 50%; background: #F1F5F9; color: #94A3B8; display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 12px;">
                                            <i class="fa-solid fa-box-open"></i>
                                        </div>
                                        <h4 style="font-weight: 700; color: var(--primary); margin-bottom: 6px; font-size: 1rem;">No Products Found</h4>
                                        <p style="font-size: 0.84rem; color: #64748B; margin-bottom: 14px;">No matching equipment found for "{{ request('q') }}".</p>
                                        <a href="{{ route('dealer.dashboard') }}" class="btn-sm btn-outline" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 0.8rem; text-decoration: none;">
                                            <i class="fa-solid fa-rotate-left"></i> View Full Price List
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Styled Pagination Section -->
                @if($products->hasPages() || $products->total() > 0)
                    <div class="dealer-pagination" style="margin-top: 20px;">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

            <!-- Confidential OEM Downloads Center -->
            <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: clamp(18px, 3vw, 28px); box-shadow: var(--shadow-sm); width: 100%; box-sizing: border-box;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--primary); margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-folder-open" style="color: #2563EB;"></i> Confidential OEM Partner Downloads
                </h3>
                <p style="font-size: 0.82rem; color: #64748B; margin-bottom: 16px;">Dealer service calibration manuals, unbranded high-res marketing kits, and firmware.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr)); gap: 14px;">
                    <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 12px; padding: 16px;">
                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--primary); margin-bottom: 4px;"><i class="fa-solid fa-file-pdf" style="color:red;"></i> 2026 OEM Price Schedule</h4>
                        <p style="font-size: 0.78rem; color: #64748B; margin-bottom: 10px;">Confidential wholesale schedule with volume rebates.</p>
                        <a href="{{ route('docs.download', ['filename' => 'brochure_aczet_2026.pdf']) }}" download="brochure_aczet_2026.pdf" class="btn-sm btn-outline" style="font-size: 0.75rem; padding: 5px 10px;">Download Price Book</a>
                    </div>

                    <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 12px; padding: 16px;">
                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--primary); margin-bottom: 4px;"><i class="fa-solid fa-screwdriver-wrench" style="color:var(--accent);"></i> Service &amp; Protocols</h4>
                        <p style="font-size: 0.78rem; color: #64748B; margin-bottom: 10px;">Factory calibration and EMFC load cell alignment guide.</p>
                        <a href="{{ route('docs.download', ['filename' => 'manual_cy224.pdf']) }}" download="manual_cy224.pdf" class="btn-sm btn-outline" style="font-size: 0.75rem; padding: 5px 10px;">Download Manual</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Purchase Order (PO) Submission Form -->
        <div style="width: 100%;">
            <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: clamp(18px, 3vw, 28px); box-shadow: var(--shadow-md); width: 100%; box-sizing: border-box; position: sticky; top: 90px;">
                <div style="margin-bottom: 16px; border-bottom: 2px solid #F1F5F9; padding-bottom: 12px;">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--primary); display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-file-invoice" style="color: var(--accent);"></i> Submit Wholesale PO
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 4px;">Generate an instant wholesale Proforma Invoice directly to factory dispatch.</p>
                </div>

                <form action="{{ route('dealer.bulk_order') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Purchase Order (PO) Number *</label>
                        <input type="text" name="po_number" required class="form-control" placeholder="e.g. PO-METRO-2026-089" value="PO-{{ strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $dealer->company_name), 0, 4)) }}-{{ date('Y') }}-{{ rand(100, 999) }}">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Select Product Model *</label>
                        <select name="product_id" id="po_product_select" required class="form-control" onchange="updateLivePoEstimate()" style="border-radius: 8px; font-size: 0.86rem;">
                            @foreach($allProducts as $p)
                                @php
                                    $unitMsrp = $p->price ?? 1450.00;
                                    $unitDealer = $unitMsrp * (1 - $discountRate);
                                @endphp
                                <option value="{{ $p->id }}" data-dealer-price="{{ $unitDealer }}" data-msrp="{{ $unitMsrp }}">
                                    {{ $p->model_number }} &mdash; {{ $p->name }} (${{ number_format($unitDealer, 2) }}/unit)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Batch Quantity (Units) *</label>
                        <input type="number" name="quantity" id="po_quantity_input" min="1" max="1000" value="5" required class="form-control" oninput="updateLivePoEstimate()" style="border-radius: 8px;">
                    </div>

                    <!-- Live Wholesale Estimation Card -->
                    <div id="live_po_summary" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; font-size: 0.82rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px; color: #64748B;">
                            <span>Wholesale Rate per Unit:</span>
                            <strong id="po_unit_rate" style="color: #0F172A;">$0.00</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px; color: #64748B;">
                            <span>Batch Order Size:</span>
                            <strong id="po_qty_display" style="color: #0F172A;">5 units</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-top: 1px solid #E2E8F0; padding-top: 7px; margin-top: 5px;">
                            <span style="font-weight: 700; color: var(--primary);">Estimated Total (USD):</span>
                            <strong id="po_total_rate" style="font-weight: 800; color: #059669; font-size: 1.05rem;">$0.00</strong>
                        </div>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Freight / Delivery Address *</label>
                        <textarea name="shipping_address" rows="3" required class="form-control" placeholder="Destination shipping port or warehouse..." style="border-radius: 8px;">{{ $dealer->address }}</textarea>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Special Packaging / Calibration Notes</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="e.g. OIML verification stamp, wooden crate export packing..." style="border-radius: 8px;"></textarea>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-weight: 800; border-radius: 10px; cursor: pointer; font-size: 0.95rem;">
                        <i class="fa-solid fa-paper-plane"></i> Place Wholesale PO
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .dealer-pagination .custom-pagination-container {
        margin-top: 18px;
        border: 1px solid #E2E8F0;
        background: #F8FAFC;
        padding: 12px 18px;
        border-radius: 10px;
        box-shadow: none;
    }
    .table-responsive .table-custom tr:hover td {
        background-color: #F8FAFC;
    }
</style>

<script>
    function updateLivePoEstimate() {
        var select = document.getElementById('po_product_select');
        var qtyInput = document.getElementById('po_quantity_input');
        if (!select || !qtyInput) return;

        var opt = select.options[select.selectedIndex];
        if (!opt) return;

        var dealerPrice = parseFloat(opt.getAttribute('data-dealer-price')) || 0;
        var qty = parseInt(qtyInput.value) || 1;
        var total = dealerPrice * qty;

        var unitEl = document.getElementById('po_unit_rate');
        var qtyEl = document.getElementById('po_qty_display');
        var totalEl = document.getElementById('po_total_rate');

        if (unitEl) unitEl.innerText = '$' + dealerPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        if (qtyEl) qtyEl.innerText = qty + ' ' + (qty === 1 ? 'unit' : 'units');
        if (totalEl) totalEl.innerText = '$' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function setPoProduct(id, model, dealerPrice) {
        var select = document.getElementById('po_product_select');
        if (select) {
            select.value = id;
            updateLivePoEstimate();
            select.scrollIntoView({ behavior: 'smooth', block: 'center' });
            select.style.borderColor = 'var(--accent)';
            select.style.boxShadow = '0 0 0 3px rgba(229, 86, 4, 0.25)';
            setTimeout(function() { 
                select.style.borderColor = 'var(--border-color)';
                select.style.boxShadow = 'none';
            }, 2000);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateLivePoEstimate();
    });
</script>
@endsection
