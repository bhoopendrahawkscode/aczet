<!-- CATEGORY LANDING PAGE (METTLER TOLEDO INDUSTRIAL WEIGHING & DIVISION SHOWCASE - SCREENSHOTS 1-4) -->
<div class="mt-category-landing">
    <!-- 1. HERO BANNER (SCREENSHOT 1) -->
    <section class="mt-cat-hero" style="background: linear-gradient(135deg, #002A3E 0%, #004D73 55%, #00608F 100%); color: #FFFFFF; padding: 40px 0 50px; position: relative; overflow: hidden;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 4%;">
            <!-- Breadcrumbs -->
            <nav style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #BAE6FD; margin-bottom: 28px; font-weight: 500;">
                <a href="{{ route('home') }}" style="color: #BAE6FD; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#BAE6FD'">Home</a>
                <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem; opacity: 0.7;"></i>
                <span style="color: #FFFFFF; font-weight: 600;">{{ $landingCategory->name }}</span>
            </nav>

            <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 40px; align-items: center;">
                <div>
                    <span style="display: inline-block; font-size: 1.25rem; font-weight: 600; color: #E0F2FE; margin-bottom: 12px; letter-spacing: 0.3px;">
                        {{ $categoryDivisionData['eyebrow'] ?? $landingCategory->name }}
                    </span>
                    <h1 style="font-size: clamp(2rem, 3.8vw, 3.2rem); font-weight: 800; line-height: 1.15; color: #FFFFFF; margin: 0 0 20px; font-family: 'Arial Narrow', Arial, sans-serif;">
                        {{ $categoryDivisionData['headline'] ?? ($landingCategory->name . ' for Industrial Excellence') }}
                    </h1>
                </div>
                <div style="display: flex; justify-content: center; align-items: center;">
                    <img src="{{ vasset($categoryDivisionData['hero_image'] ?? 'images/products/is600.svg') }}" 
                         alt="{{ $landingCategory->name }}" 
                         style="max-height: 280px; max-width: 100%; object-fit: contain; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.3));"
                         onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                </div>
            </div>
        </div>
    </section>

    <!-- 2. INTRO PARAGRAPH (SCREENSHOT 2) -->
    <section style="background: #FFFFFF; padding: 48px 0 32px;">
        <div style="max-width: 1080px; margin: 0 auto; padding: 0 20px; text-align: center;">
            <p style="font-size: 1.05rem; line-height: 1.75; color: #334155; margin: 0; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                {{ $categoryDivisionData['intro'] ?? '' }}
            </p>
        </div>
    </section>

    <!-- 3. SUBCATEGORY / SOLUTION CARDS (3 COLUMNS GRID - SCREENSHOT 2) -->
    <section style="background: #FFFFFF; padding: 10px 0 60px;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 4%;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 28px;">
                @foreach($landingCategory->children as $child)
                    <div class="mt-subcat-card" style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 3px; display: flex; flex-direction: column; overflow: hidden; transition: all 0.25s ease;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.08)'; this.style.borderColor='#CBD5E1';" onmouseout="this.style.boxShadow='none'; this.style.borderColor='#E5E7EB';">
                        <!-- Top Image Stage -->
                        <a href="{{ route('products.index', ['category' => $child->slug]) }}" style="height: 220px; display: flex; align-items: center; justify-content: center; padding: 24px; background: #FFFFFF; text-decoration: none;">
                            <img src="{{ vasset($child->representative_image ?? $child->image) }}" 
                                 alt="{{ $child->name }}" 
                                 loading="lazy"
                                 style="max-height: 160px; max-width: 90%; object-fit: contain; transition: transform 0.3s ease;"
                                 onmouseover="this.style.transform='scale(1.04)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                        </a>

                        <!-- Horizontal Divider -->
                        <div style="height: 1px; background: #E5E7EB; width: 100%;"></div>

                        <!-- Card Content -->
                        <div style="padding: 22px 24px 24px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="font-size: 1.15rem; font-weight: 700; line-height: 1.35; margin: 0 0 10px;">
                                    <a href="{{ route('products.index', ['category' => $child->slug]) }}" style="color: #00608F; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#004466'" onmouseout="this.style.color='#00608F'">
                                        {{ $child->name }}
                                    </a>
                                </h3>
                                <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 8px; margin-bottom: 20px;">
                                    <p style="font-size: 0.86rem; color: #4B5563; line-height: 1.5; margin: 0;">
                                        {{ Str::limit($child->description ?: ('High performance ' . strtolower($child->name) . ' engineered for rugged industrial duty and precision compliance.'), 125) }}
                                    </p>
                                    <a href="{{ route('products.index', ['category' => $child->slug]) }}" style="color: #00608F; text-decoration: none;" title="Explore category">
                                        <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem;"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Solid Theme Get a Quote Button -->
                            <button type="button" 
                                    onclick="openQuoteModal('Inquiry for {{ addslashes($child->name) }}', null, '{{ addslashes($child->name) }}')"
                                    style="width: 100%; background-color: #173887; color: #FFFFFF; font-weight: 700; font-size: 0.95rem; padding: 11px 20px; border-radius: 3px; border: none; cursor: pointer; text-align: center; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='#102761'" 
                                    onmouseout="this.style.backgroundColor='#173887'">
                                Get a Quote
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. USEFUL RELATED LINKS (SCREENSHOT 3) -->
    <section style="background: #F8FAFC; padding: 60px 0; border-top: 1px solid #E2E8F0; border-bottom: 1px solid #E2E8F0;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 4%;">
            <h2 style="font-size: 1.85rem; font-weight: 700; color: #00608F; margin: 0 0 32px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                Useful Related Links
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 28px;">
                <!-- Link Card 1 -->
                <div style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 3px; padding: 32px 28px; display: flex; flex-direction: column;">
                    <div style="height: 180px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                        <img src="{{ vasset($categoryDivisionData['catalog_image'] ?? 'images/banner-industry.jpg') }}" 
                             alt="{{ $categoryDivisionData['catalog_title'] ?? 'Catalog' }}" 
                             style="max-height: 100%; max-width: 100%; object-fit: contain; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"
                             onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                    </div>
                    <h3 style="font-size: 1.18rem; font-weight: 700; margin: 0 0 12px;">
                        <a href="{{ route('technical_publications') }}" style="color: #00608F; text-decoration: none;">
                            {{ $categoryDivisionData['catalog_title'] ?? 'Industrial Weighing Catalog 2026' }}
                        </a>
                    </h3>
                    <p style="font-size: 0.88rem; color: #4B5563; line-height: 1.55; margin: 0 0 18px; flex: 1;">
                        {{ $categoryDivisionData['catalog_desc'] ?? 'Download your comprehensive product catalog for complete specifications and selection guides.' }}
                    </p>
                    <a href="{{ route('technical_publications') }}" style="color: #00608F; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        Download Catalog <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>

                <!-- Link Card 2 -->
                <div style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 3px; padding: 32px 28px; display: flex; flex-direction: column;">
                    <div style="height: 180px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                        <img src="{{ vasset($categoryDivisionData['comp_image'] ?? 'images/products/is600.svg') }}" 
                             alt="{{ $categoryDivisionData['comp_title'] ?? 'Component Catalog' }}" 
                             style="max-height: 100%; max-width: 100%; object-fit: contain;"
                             onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                    </div>
                    <h3 style="font-size: 1.18rem; font-weight: 700; margin: 0 0 12px;">
                        <a href="{{ route('about.technology') }}" style="color: #00608F; text-decoration: none;">
                            {{ $categoryDivisionData['comp_title'] ?? 'Weighing Component Catalog' }}
                        </a>
                    </h3>
                    <p style="font-size: 0.88rem; color: #4B5563; line-height: 1.55; margin: 0 0 18px; flex: 1;">
                        {{ $categoryDivisionData['comp_desc'] ?? 'Aczet manufactures high-precision weighing components for builders of instruments, machinery, and process plants.' }}
                    </p>
                    <a href="{{ route('about.technology') }}" style="color: #00608F; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        Learn More <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>

                <!-- Link Card 3 -->
                <div style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 3px; padding: 32px 28px; display: flex; flex-direction: column;">
                    <div style="height: 180px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                        <img src="{{ vasset($categoryDivisionData['hub_image'] ?? 'images/banner-vision.jpg') }}" 
                             alt="{{ $categoryDivisionData['hub_title'] ?? 'Engineering Hub' }}" 
                             style="max-height: 100%; max-width: 100%; object-fit: cover; border-radius: 4px;"
                             onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                    </div>
                    <h3 style="font-size: 1.18rem; font-weight: 700; margin: 0 0 12px;">
                        <a href="{{ route('webinars') }}" style="color: #00608F; text-decoration: none;">
                            {{ $categoryDivisionData['hub_title'] ?? 'Engineering Hub' }}
                        </a>
                    </h3>
                    <p style="font-size: 0.88rem; color: #4B5563; line-height: 1.55; margin: 0 0 18px; flex: 1;">
                        {{ $categoryDivisionData['hub_desc'] ?? 'Expand your technical expertise with white papers, webinars, podcasts, and application guides from metrology experts.' }}
                    </p>
                    <a href="{{ route('webinars') }}" style="color: #00608F; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        Explore Knowledge Hub <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. UNLOCK SERVICE EXCELLENCE SPLIT BANNER (SCREENSHOT 3 & 4) -->
    <section style="background: #FFFFFF; padding: 70px 0 50px;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 4%;">
            <div style="display: grid; grid-template-columns: 1fr 1.25fr; border-radius: 4px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06); min-height: 380px;">
                <!-- Left Column: Technician Image with Modern Overlay -->
                <div style="background: url('{{ vasset('images/banner-service.jpg') }}') center/cover no-repeat; min-height: 320px; position: relative;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,42,62,0.1), rgba(0,42,62,0.4));"></div>
                </div>

                <!-- Right Column: Deep Blue Service Box -->
                <div style="background-color: #003F66; color: #FFFFFF; padding: 50px 48px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <h2 style="font-size: clamp(1.6rem, 2.5vw, 2.2rem); font-weight: 800; color: #FFFFFF; margin: 0 0 24px; line-height: 1.25; font-family: 'Arial Narrow', Arial, sans-serif;">
                            Unlock Service Excellence - Maximize Your Efficiency
                        </h2>
                        
                        <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 32px;">
                            <a href="{{ route('service_registration') }}" style="color: #BAE6FD; text-decoration: none; font-size: 0.98rem; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; transition: color 0.2s;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#BAE6FD'">
                                Repair Services and Spare Parts <i class="fa-solid fa-chevron-right" style="font-size: 0.72rem;"></i>
                            </a>
                            <a href="{{ route('about.technology') }}" style="color: #BAE6FD; text-decoration: none; font-size: 0.98rem; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; transition: color 0.2s;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#BAE6FD'">
                                ISO 17025 Accredited Calibration Services <i class="fa-solid fa-chevron-right" style="font-size: 0.72rem;"></i>
                            </a>
                            <a href="{{ route('about.index') }}" style="color: #BAE6FD; text-decoration: none; font-size: 0.98rem; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; transition: color 0.2s;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#BAE6FD'">
                                Plus many more - see all <i class="fa-solid fa-chevron-right" style="font-size: 0.72rem;"></i>
                            </a>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 24px;">
                        <a href="{{ route('service_registration') }}" 
                           style="background-color: #173887; color: #FFFFFF; font-weight: 700; font-size: 1rem; padding: 13px 32px; border-radius: 3px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background-color 0.2s;"
                           onmouseover="this.style.backgroundColor='#102761'" 
                           onmouseout="this.style.backgroundColor='#173887'">
                            Request Service
                        </a>

                        <a href="tel:1800228884" style="color: #FFFFFF; font-size: 1.15rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-phone" style="color: #38BDF8; font-size: 1rem;"></i> Toll Free 1800 22 8884
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. FAQS ACCORDION (SCREENSHOT 4) -->
    <section style="background: #FFFFFF; padding: 40px 0 70px;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 4%;">
            <h2 style="font-size: 1.85rem; font-weight: 700; color: #1E293B; margin: 0 0 24px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                FAQs
            </h2>

            <div style="border-top: 1px solid #E2E8F0;">
                @foreach(($categoryDivisionData['faqs'] ?? []) as $index => $faq)
                    <div class="category-faq-row" style="border-bottom: 1px solid #E2E8F0;">
                        <button type="button" 
                                onclick="toggleCategoryFaqItem(this)" 
                                style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 20px 0; background: none; border: none; text-align: left; cursor: pointer; color: #1E293B; font-size: 1.05rem; font-weight: 600; font-family: inherit;">
                            <span>{{ $faq['q'] }}</span>
                            <i class="fa-solid fa-chevron-down faq-arrow" style="color: #00608F; font-size: 0.9rem; transition: transform 0.25s ease;"></i>
                        </button>
                        <div class="category-faq-body" style="display: none; padding: 0 0 22px; color: #475569; font-size: 0.94rem; line-height: 1.7;">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. BROWSE ALL PRODUCTS IN THIS DIVISION CTA -->
    <section style="background: #F1F5F9; padding: 40px 0; text-align: center; border-top: 1px solid #E2E8F0;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 4%;">
            <p style="font-size: 1.1rem; color: #334155; margin: 0 0 16px; font-weight: 600;">
                Looking for specific models or complete specifications?
            </p>
            <a href="{{ route('products.index', ['category' => $landingCategory->slug, 'view_all' => 1]) }}" 
               style="display: inline-flex; align-items: center; gap: 10px; background-color: #00608F; color: #FFFFFF; font-weight: 700; font-size: 1rem; padding: 13px 34px; border-radius: 3px; text-decoration: none; transition: background-color 0.2s;"
               onmouseover="this.style.backgroundColor='#004466'" 
               onmouseout="this.style.backgroundColor='#00608F'">
                <span>Browse All {{ $landingCategory->name }} Products</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>
</div>

<script>
    function toggleCategoryFaqItem(btn) {
        const body = btn.nextElementSibling;
        const arrow = btn.querySelector('.faq-arrow');
        const isOpen = body.style.display === 'block';

        if (isOpen) {
            body.style.display = 'none';
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        } else {
            body.style.display = 'block';
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
    }
</script>

