<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\ProductFaq;
use App\Models\ProductRegionVisibility;
use App\Models\Exhibition;
use App\Models\Blog;
use App\Models\Career;
use App\Models\Lead;
use App\Models\Dealer;
use App\Models\ServiceRegistration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@aczet.com'],
            [
                'name' => 'Aczet Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Core 5 Parent Divisions (Matching Document Catalog & MT.com structure)
        $parentLab = Category::firstOrCreate(
            ['slug' => 'laboratory-weighing'],
            [
                'name' => 'Laboratory Weighing',
                'description' => 'Precision Laboratory Weighing instruments.',
                'image' => '/images/products/cy224.svg',
                'parent_id' => null,
            ]
        );

        $parentIndWeigh = Category::firstOrCreate(
            ['slug' => 'industrial-weighing-solutions'],
            [
                'name' => 'Industrial Weighing Solutions',
                'description' => 'Industrial scales, weighbridges, and load cells.',
                'image' => '/images/products/is600.svg',
                'parent_id' => null,
            ]
        );

        $parentJewelry = Category::firstOrCreate(
            ['slug' => 'jewelry-machines'],
            [
                'name' => 'Jewelry Machines',
                'description' => 'Jewelry scales, XRF gold testing machines, and laser welders.',
                'image' => '/images/products/gxz600.svg',
                'parent_id' => null,
            ]
        );

        $parentIndMach = Category::firstOrCreate(
            ['slug' => 'industrial-machines'],
            [
                'name' => 'Industrial Machines',
                'description' => 'XRF coating thickness and laser engraving machines.',
                'image' => '/images/products/is600.svg',
                'parent_id' => null,
            ]
        );

        $parentLabInst = Category::firstOrCreate(
            ['slug' => 'laboratory-instruments'],
            [
                'name' => 'Laboratory Instruments',
                'description' => 'Laboratory instruments, polarimeters, refractometers, and ultrasonic cleaners.',
                'image' => '/images/products/cy224.svg',
                'parent_id' => null,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'industrial-machines-xrf-coating-thickness'],
            [
                'name' => 'XRF Coating Thickness Measurement',
                'description' => 'Industrial XRF machines.',
                'parent_id' => $parentIndMach->id,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'laboratory-instruments-refractometers'],
            [
                'name' => 'Refractometers',
                'description' => 'Laboratory refractometers.',
                'parent_id' => $parentLabInst->id,
            ]
        );

        Category::whereIn('slug', ['check-weighers-metal-detectors'])->update(['parent_id' => $parentIndWeigh->id]);
        Category::whereIn('slug', ['ultrasonic-cleaners'])->update(['parent_id' => $parentLabInst->id]);
        Category::whereIn('slug', ['standard-calibration-weights', 'mass-comparators'])->update(['parent_id' => $parentLab->id]);

        // Categories
        $catAnalytical = Category::create([
            'parent_id' => $parentLab->id,
            'name' => 'Analytical Balances',
            'slug' => 'analytical-balances',
            'description' => 'High-precision analytical balances designed for laboratory research and pharmaceutical applications with 0.1mg to 0.01mg readability.',
            'image' => '/images/products/cy224.svg',
            'translations' => [
                'es' => ['name' => 'Balanzas Analíticas de Precisión', 'description' => 'Balanzas analíticas de alta precisión diseñadas para investigación de laboratorio y farmacéutica con legibilidad de 0.1mg a 0.01mg.'],
                'ar' => ['name' => 'موازين تحليلية فائقة الدقة', 'description' => 'موازين تحليلية عالية الدقة مصممة للأبحاث المخبرية والصناعات الدوائية بدقة قراءة من 0.1 مجم إلى 0.01 مجم.'],
                'de' => ['name' => 'Präzisions-Analysenwaagen', 'description' => 'Hochpräzise Analysenwaagen für Laborforschung und pharmazeutische Anwendungen mit 0,1 mg bis 0,01 mg Ablesbarkeit.'],
                'zh' => ['name' => '高精度分析天平', 'description' => '专为实验室研究和制药应用设计的高精度分析天平，可读性从0.1mg到0.01mg。'],
                'ja' => ['name' => '高精度分析天びん', 'description' => '研究室や製薬用途向けに設計された、0.1mg〜0.01mgの読取限度を持つ高精度分析天びん。'],
                'th' => ['name' => 'เครื่องชั่งวิเคราะห์ความละเอียดสูง', 'description' => 'เครื่องชั่งวิเคราะห์ความแม่นยำสูงสำหรับการวิจัยในห้องปฏิบัติการและเภสัชกรรม ความละเอียด 0.1mg ถึง 0.01mg'],
                'fr' => ['name' => 'Balances Analytiques de Précision', 'description' => 'Balances analytiques de haute précision conçues pour la recherche en laboratoire et la pharmacie.'],
                'it' => ['name' => 'Bilance Analitiche di Precisione', 'description' => 'Bilance analitiche ad alta precisione per ricerca di laboratorio e applicazioni farmaceutiche.'],
                'ru' => ['name' => 'Прецизионные аналитические весы', 'description' => 'Высокоточные аналитические весы для лабораторных исследований и фармацевтики с дискретностью от 0.1 мг.'],
                'pt' => ['name' => 'Balanças Analíticas de Precisão', 'description' => 'Balanças analíticas de alta precisão para pesquisa laboratorial e indústria farmacêutica.'],
                'ko' => ['name' => '정밀 분석 전자저울', 'description' => '실험실 연구 및 제약 분야를 위해 설계된 0.1mg ~ 0.01mg 판독 한도의 고정밀 분석 저울.'],
            ]
        ]);

        $catMicro = Category::create([
            'parent_id' => $parentLab->id,
            'name' => 'Micro & Semi-Micro Balances',
            'slug' => 'micro-balances',
            'description' => 'Ultra precision micro balances with 1µg resolution for nanogram level weighing in chemical synthesis.',
            'image' => '/images/products/cm21.svg',
            'translations' => [
                'es' => ['name' => 'Micro y Semi-Micro Balanzas', 'description' => 'Microbalanzas de ultra precisión con resolución de 1µg para pesaje a nivel de nanogramos en síntesis química.'],
                'ar' => ['name' => 'موازين ميكرو وشبه ميكرو', 'description' => 'موازين ميكرو فائقة الدقة بدقة 1 ميكروجرام لوزن النانوجرام في التركيبات الكيميائية الدقيقة.'],
                'de' => ['name' => 'Mikro- & Halbmikrowaagen', 'description' => 'Ultrapräzise Mikrowaagen mit 1 µg Auflösung für Wägungen im Nanogrammbereich in der chemischen Synthese.'],
                'zh' => ['name' => '微量与超微量天平', 'description' => '分辨率达1µg的超高精度微量天平，用于化学合成中的纳克级称重。'],
                'ja' => ['name' => 'マイクロ＆セミマイクロ天びん', 'description' => '化学合成におけるナノグラム測定に対応する、分解能1µgの超高精度マイクロ天びん。'],
                'th' => ['name' => 'เครื่องชั่งไมโครและเซมิไมโคร', 'description' => 'เครื่องชั่งไมโครความเที่ยงตรงสูงพิเศษ ความละเอียด 1µg สำหรับการชั่งระดับนาโนกรัม'],
                'fr' => ['name' => 'Micro & Semi-Micro Balances', 'description' => 'Microbalances de très haute précision avec résolution de 1µg pour pesage nanogramme.'],
                'it' => ['name' => 'Micro e Semi-Micro Bilance', 'description' => 'Microbilance ad altissima precisione con risoluzione di 1µg per pesate nanometriche.'],
                'ru' => ['name' => 'Микро- и полумикровесы', 'description' => 'Сверхточные микровесы с разрешением 1 мкг для взвешивания нанограммов.'],
                'pt' => ['name' => 'Micro e Semi-Micro Balanças', 'description' => 'Microbalanças de ultra precisão com resolução de 1µg para pesagens em nanogramas.'],
                'ko' => ['name' => '마이크로 및 세미 마이크로 저울', 'description' => '화학 합성에서 나노그램 단위 계량을 지원하는 1µg 분해능의 초정밀 마이크로 저울.'],
            ]
        ]);

        $catMoisture = Category::create([
            'parent_id' => $parentLab->id,
            'name' => 'Moisture Analyzers',
            'slug' => 'moisture-analyzers',
            'description' => 'Halogen moisture determination instruments providing fast and reliable moisture content measurement.',
            'image' => '/images/products/mb200.svg',
            'translations' => [
                'es' => ['name' => 'Analizadores de Humedad', 'description' => 'Instrumentos de determinación de humedad halógena que proporcionan mediciones rápidas y fiables de contenido de humedad.'],
                'ar' => ['name' => 'أجهزة تحليل الرطوبة', 'description' => 'أجهزة تحديد الرطوبة بالهالوجين توفر قياساً سريعاً وموثوقاً لنسبة الرطوبة في العينات الغذائية والصيدلانية.'],
                'de' => ['name' => 'Halogen-Feuchtebestimmer', 'description' => 'Halogen-Feuchtebestimmungsgeräte für schnelle und zuverlässige Feuchtegehaltsmessungen.'],
                'zh' => ['name' => '卤素水分测定仪', 'description' => '卤素加热水分测定仪器，为食品和化工样品提供快速可靠的水分含量分析。'],
                'ja' => ['name' => 'ハロゲン水分計・分析計', 'description' => '食品や化学サンプルの水分含有量を迅速かつ正確に測定するハロゲン加熱式水分分析計。'],
                'th' => ['name' => 'เครื่องวิเคราะห์ความชื้น', 'description' => 'เครื่องวัดความชื้นระบบฮาโลเจน ให้ผลการวัดปริมาณความชื้นที่รวดเร็วและแม่นยำ'],
                'fr' => ['name' => 'Dessiccateurs Halogènes', 'description' => 'Instruments de détermination de l\'humidité par halogène rapides et fiables.'],
                'it' => ['name' => 'Analizzatori di Umidità Alogeni', 'description' => 'Strumenti di misura dell\'umidità alogeni per analisi rapide e affidabili.'],
                'ru' => ['name' => 'Галогенные анализаторы влажности', 'description' => 'Приборы для быстрого и надежного термогравиметрического определения влаги.'],
                'pt' => ['name' => 'Analisadores de Humidade', 'description' => 'Equipamentos de determinação de humidade por halogéneo rápidos e confiáveis.'],
                'ko' => ['name' => '할로겐 수분측정기', 'description' => '식품, 제약 및 화학 시료의 수분 함량을 빠르고 신뢰성 있게 측정하는 할로겐 수분분석기.'],
            ]
        ]);

        $catIndustrial = Category::create([
            'parent_id' => $parentIndWeigh->id,
            'name' => 'Industrial Scales',
            'slug' => 'industrial-scales',
            'description' => 'Heavy-duty stainless steel platform scales, crane scales, and bench scales for demanding manufacturing floors.',
            'image' => '/images/products/is600.svg',
            'translations' => [
                'es' => ['name' => 'Básculas Industriales', 'description' => 'Básculas de plataforma de acero inoxidable de alta resistencia para entornos de fabricación exigentes.'],
                'ar' => ['name' => 'موازين ومنصات صناعية', 'description' => 'موازين منصات ثقيلة من الفولاذ المقاوم للصدأ وموازين رافعات مصممة للمصانع والبيئات الصناعية القاسية.'],
                'de' => ['name' => 'Industriewaagen & Plattformen', 'description' => 'Robuste Edelstahl-Plattformwaagen und Kranwaagen für anspruchsvolle Produktionsumgebungen.'],
                'zh' => ['name' => '工业称重平台与台秤', 'description' => '重型不锈钢平台秤、吊秤和台秤，适用于严苛的工业制造现场。'],
                'ja' => ['name' => '産業用プラットフォームスケール', 'description' => '過酷な製造現場に対応する頑丈なステンレス製台秤およびプラットフォームスケール。'],
                'th' => ['name' => 'เครื่องชั่งอุตสาหกรรม', 'description' => 'แท่นชั่งสแตนเลสสตีลสำหรับงานหนัก ทนทานสูงสำหรับโรงงานอุตสาหกรรม'],
                'fr' => ['name' => 'Balances & Plates-formes Industrielles', 'description' => 'Balances de plate-forme en acier inoxydable pour environnements industriels exigeants.'],
                'it' => ['name' => 'Bilance e Piattaforme Industriali', 'description' => 'Bilance a piattaforma in acciaio inox per impieghi gravosi in ambienti industriali.'],
                'ru' => ['name' => 'Промышленные платформенные весы', 'description' => 'Сверхнадежные весовые платформы из нержавеющей стали для жестких условий производства.'],
                'pt' => ['name' => 'Balanças Industriais', 'description' => 'Balanças de plataforma em aço inoxidável para ambientes industriais exigentes.'],
                'ko' => ['name' => '산업용 플랫폼 저울', 'description' => '열악한 생산 현장을 위한 고내구성 스테인리스 스틸 플랫폼 저울 및 크레인 저울.'],
            ]
        ]);

        $catJewel = Category::create([
            'parent_id' => $parentJewelry->id,
            'name' => 'Jewelry & Carat Balances',
            'slug' => 'jewelry-balances',
            'description' => 'Government approved carat and gold balances for gemologists, jewelry showrooms, and assaying centers.',
            'image' => '/images/products/gxz600.svg',
            'translations' => [
                'es' => ['name' => 'Balanzas de Joyería y Quilates', 'description' => 'Balanzas de oro y quilates homologadas para gemólogos, joyerías y centros de ensayo.'],
                'ar' => ['name' => 'موازين الذهب والمجوهرات والقيراط', 'description' => 'موازين قياس قيراط الذهب والألماس المعتمدة قانونياً لخبراء الأحجار الكريمة وصالات المجوهرات ومراكز الفحص.'],
                'de' => ['name' => 'Juwelier- & Karatwaagen', 'description' => 'Zugelassene Karat- und Goldwaagen für Gemmologen, Juweliere und Prüflabore.'],
                'zh' => ['name' => '黄金珠宝与克拉天平', 'description' => '经权威计检认证的克拉和黄金天平，适用于宝石学家、珠宝展厅和贵金属检验中心。'],
                'ja' => ['name' => '宝飾・カラット・ジュエリー天びん', 'description' => '宝石鑑定士、ジュエリーショールーム、貴金属検査機関向けの検定取得済みカラット天びん。'],
                'th' => ['name' => 'เครื่องชั่งทองและเพชรพลอยกะรัต', 'description' => 'เครื่องชั่งกะรัตและทองคำที่ผ่านการรับรอง สำหรับนักอัญมณีศาสตร์และร้านทอง'],
                'fr' => ['name' => 'Balances Joaillerie & Carats', 'description' => 'Balances homologuées pour l\'or et les carats pour gemmologues et bijouteries.'],
                'it' => ['name' => 'Bilance per Oreficeria e Carati', 'description' => 'Bilance per oro e carati omologate per gemmologi, laboratori e gioiellerie.'],
                'ru' => ['name' => 'Ювелирные и каратные весы', 'description' => 'Весы для взвешивания золота, драгоценных камней и каратов с официальной поверкой.'],
                'pt' => ['name' => 'Balanças de Ourivesaria e Quilates', 'description' => 'Balanças aprovadas para ouro e quilates para joalharias e centros de análise.'],
                'ko' => ['name' => '귀금속 및 캐럿 다이아몬드 저울', 'description' => '보석 감정원, 주얼리 쇼룸 및 분석 센터를 위한 공인 캐럿 및 금 전자저울.'],
            ]
        ]);

        // Products
        $p1 = Product::create([
            'category_id' => $catAnalytical->id,
            'name' => 'Aczet CY 224 Precision Analytical Balance',
            'model_number' => 'CY-224C',
            'slug' => 'aczet-cy-224-precision-analytical-balance',
            'short_description' => '220g capacity with 0.0001g (0.1mg) resolution, internal motorized calibration, and backlit graphical display.',
            'full_description' => 'The Aczet CY 224 is an industry-leading analytical balance featuring MonoTech technology. It features high stability, rapid stabilization time under 2 seconds, motorized internal calibration with automatic temperature adjustment (isoCAL), and RS232/USB connectivity for GLP compliance.',
            'main_image' => '/images/products/cy224.svg',
            'gallery' => [
                '/images/products/cy224.svg',
                '/images/products/cm21.svg'
            ],
            'pdf_datasheet' => '/docs/cy224_datasheet.pdf',
            'pdf_manual' => '/docs/cy224_manual.pdf',
            'pdf_brochure' => '/docs/aczet_analytical_brochure.pdf',
            'price' => 1850.00,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $p2 = Product::create([
            'category_id' => $catMicro->id,
            'name' => 'Aczet CM 21 Ultra Micro Balance',
            'model_number' => 'CM-21U',
            'slug' => 'aczet-cm-21-ultra-micro-balance',
            'short_description' => '21g capacity with 0.000001g (1µg) resolution, motorized glass draft shield, and touch screen interface.',
            'full_description' => 'Engineered for extreme precision in research laboratories, filter weighing, and nanotechnology. The CM 21 features dual glass draft shields, automatic draft shield door opening with infrared sensors, and audit trail support complying with FDA 21 CFR Part 11.',
            'main_image' => '/images/products/cm21.svg',
            'gallery' => [
                '/images/products/cm21.svg'
            ],
            'pdf_datasheet' => '/docs/cm21_datasheet.pdf',
            'pdf_manual' => '/docs/cm21_manual.pdf',
            'pdf_brochure' => '/docs/aczet_micro_brochure.pdf',
            'price' => 7490.00,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $pAxr2ua = Product::create([
            'category_id' => $catMicro->id,
            'name' => 'Ultra-Microbalance AXR 2UA',
            'model_number' => 'AXR 2UA',
            'slug' => 'ultra-microbalance-axr-2ua',
            'short_description' => 'Next-generation ultra microbalance designed for extremely precise weighing of ultra-light samples. With 2.1 g capacity and 0.1 µg readability, the AXR 2UA delivers exceptional accuracy for advanced laboratory applications. It features a colour TFT touchscreen, automatic motorised internal calibration with PSAC, and a user-friendly interface for reliable, secure, and efficient micro-weighing performance.',
            'full_description' => 'Next-generation ultra microbalance designed for extremely precise weighing of ultra-light samples. With 2.1 g capacity and 0.1 µg readability, the AXR 2UA delivers exceptional accuracy for advanced laboratory applications.',
            'main_image' => 'images/products/axr-microbalance.png',
            'gallery' => [
                'images/products/axr-microbalance.png'
            ],
            'price' => 9500.00,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $pAxr2ma = Product::create([
            'category_id' => $catMicro->id,
            'name' => 'Microbalance AXR 2MA',
            'model_number' => 'AXR 2MA',
            'slug' => 'microbalance-axr-2ma',
            'short_description' => 'Next-generation weighing technology with a solid, durable construction for long-term reliability. High-precision performance with 2.1 g capacity and 1 µg readability for accurate micro-weighing. Intuitive color TFT touchscreen display with internal calibration and easy leveling alerts. Supports 21 CFR part 11 compliant user management for secure and regulated laboratory operations. Multiple connectivity options including USB, RS232, and Ethernet for seamless data integration. Compact design with overload protection and power-saving mode, ideal for modern laboratories.',
            'full_description' => 'Next-generation weighing technology with a solid, durable construction for long-term reliability. High-precision performance with 2.1 g capacity and 1 µg readability for accurate micro-weighing.',
            'main_image' => 'images/products/axr-microbalance.png',
            'gallery' => [
                'images/products/axr-microbalance.png'
            ],
            'price' => 8200.00,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $pAxr2mf = Product::create([
            'category_id' => $catMicro->id,
            'name' => 'Microbalance Filter AXR 2MF',
            'model_number' => 'AXR 2MF',
            'slug' => 'microbalance-filter-axr-2mf',
            'short_description' => 'Advanced weighing technology with a solid, robust construction designed for filter microbalance applications. High-accuracy performance with 2.1 capacity and 1 µg readability for ultra-precise measurements. Ideal for filter weighing in environmental monitoring, air quality analysis, and research labs. It complies with regulatory standards, including 21 CFR Part 11, OIML R111, EU 2014/31/EU, and other metrology directives. Discover a high-end, optimized for daily laboratory activities, RS232, and Ethernet connectivity. Compact footprint with overload detection and power-failure mode, optimized for daily laboratory activities.',
            'full_description' => 'Advanced weighing technology with a solid, robust construction designed for filter microbalance applications. High-accuracy performance with 2.1 capacity and 1 µg readability for ultra-precise measurements.',
            'main_image' => 'images/products/axr-microbalance.png',
            'gallery' => [
                'images/products/axr-microbalance.png'
            ],
            'price' => 8600.00,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $p3 = Product::create([
            'category_id' => $catMoisture->id,
            'name' => 'Aczet MB 200 Touchscreen Halogen Moisture Analyzer',
            'model_number' => 'MB-200T',
            'slug' => 'aczet-mb-200-touchscreen-moisture-analyzer',
            'short_description' => '200g max capacity, 0.001g moisture readability, 400W halogen heating lamp up to 200°C.',
            'full_description' => 'Designed for rapid moisture determination in food, pharma, plastics, and chemical samples. Color touchscreen LCD display with real-time drying curve graphs, customizable heating profiles (Standard, Fast, Step, Gentle), and memory for 100 drying programs.',
            'main_image' => '/images/products/mb200.svg',
            'gallery' => [
                '/images/products/mb200.svg'
            ],
            'pdf_datasheet' => '/docs/mb200_datasheet.pdf',
            'pdf_manual' => '/docs/mb200_manual.pdf',
            'pdf_brochure' => '/docs/aczet_moisture_brochure.pdf',
            'price' => 2950.00,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $p4 = Product::create([
            'category_id' => $catIndustrial->id,
            'name' => 'Aczet Heavy Duty Stainless Platform Scale IP68',
            'model_number' => 'IS-600S',
            'slug' => 'aczet-heavy-duty-platform-scale-ip68',
            'short_description' => '600kg capacity, 10g division, washdown IP68 hermetically sealed load cell.',
            'full_description' => 'Built for food processing and chemical plants. All 304 stainless steel construction with heavy duty diamond plate pan, overload protection up to 150%, battery backup, and high-visibility LED display.',
            'main_image' => '/images/products/is600.svg',
            'gallery' => [],
            'pdf_datasheet' => '/docs/is600_datasheet.pdf',
            'pdf_brochure' => '/docs/aczet_industrial_brochure.pdf',
            'price' => 1200.00,
            'is_featured' => false,
            'is_active' => true,
        ]);

        $p5 = Product::create([
            'category_id' => $catJewel->id,
            'name' => 'Aczet GXZ 600 Diamond & Carat Scale',
            'model_number' => 'GXZ-600',
            'slug' => 'aczet-gxz-600-diamond-carat-scale',
            'short_description' => '600ct (120g) capacity with 0.001ct (0.0001g) precision, windshield included.',
            'full_description' => 'Approved by international weights & measures authorities. Features carat, gram, grain, and troy ounce units. Includes gem bowl, calibration weights, and density determination kit for gemstone verification.',
            'main_image' => '/images/products/gxz600.svg',
            'gallery' => [],
            'pdf_datasheet' => '/docs/gxz600_datasheet.pdf',
            'pdf_brochure' => '/docs/aczet_jewelry_brochure.pdf',
            'price' => 980.00,
            'is_featured' => false,
            'is_active' => true,
        ]);

        $p6 = Product::create([
            'category_id' => $catIndustrial->id,
            'name' => 'Aczet High Speed Checkweigher',
            'model_number' => 'CW-H',
            'slug' => 'aczet-high-speed-checkweigher',
            'short_description' => 'Industrial PC based high-speed checkweigher with user-friendly operation and automatic zeroing.',
            'full_description' => 'Industrial PC based high-speed checkweigher with user-friendly operation and automatic zeroing.',
            'main_image' => '/images/products/checkweigher-cwh.jpg',
            'gallery' => [],
            'pdf_datasheet' => '/docs/cwh_datasheet.pdf',
            'pdf_brochure' => '/docs/aczet_industrial_brochure.pdf',
            'price' => 4500.00,
            'is_featured' => true,
            'is_active' => true,
        ]);

        // Specifications
        $specs = [
            [$p1->id, 'Weighing Performance', 'Max Capacity', '220 g', 1],
            [$p1->id, 'Weighing Performance', 'Readability', '0.0001 g (0.1 mg)', 2],
            [$p1->id, 'Weighing Performance', 'Repeatability', '± 0.0001 g', 3],
            [$p1->id, 'Weighing Performance', 'Linearity', '± 0.0002 g', 4],
            [$p1->id, 'Physical Specifications', 'Pan Size', '90 mm Diameter Stainless Steel', 5],
            [$p1->id, 'Physical Specifications', 'Draft Shield', '3-door Glass Chamber', 6],
            [$p1->id, 'System & Interface', 'Calibration', 'Motorized Internal Automatic (isoCAL)', 7],
            [$p1->id, 'System & Interface', 'Connectivity', 'USB Device, RS-232C, Ethernet', 8],

            [$p2->id, 'Weighing Performance', 'Max Capacity', '21 g', 1],
            [$p2->id, 'Weighing Performance', 'Readability', '0.000001 g (1 µg)', 2],
            [$p2->id, 'System & Interface', 'Draft Shield', 'Motorized IR Sensor Auto-Door', 3],
            [$p2->id, 'System & Interface', 'Compliance', 'FDA 21 CFR Part 11 Audit Trail', 4],

            [$pAxr2ua->id, 'Weighing Performance', 'Weighing Capacity', '2.1 g', 1],
            [$pAxr2ua->id, 'Weighing Performance', 'Readability', '0.1 µg', 2],
            [$pAxr2ua->id, 'Weighing Performance', 'Minimum Weight (USP, 0.1%, typical)', '0.5 mg', 3],
            [$pAxr2ua->id, 'Classification', 'Series', 'AXR', 4],
            [$pAxr2ua->id, 'Classification', 'Type', 'Ultra Microbalances', 5],
            [$pAxr2ua->id, 'Features', 'Display', 'Colour TFT Touchscreen', 6],
            [$pAxr2ua->id, 'Features', 'Calibration', 'Automatic Motorised Internal Calibration (PSAC )', 7],
            [$pAxr2ua->id, 'Classification', 'Class', 'Premium ($$$$)', 8],

            [$pAxr2ma->id, 'Weighing Performance', 'Weighing Capacity', '2.1 g', 1],
            [$pAxr2ma->id, 'Weighing Performance', 'Readability', '1 µg', 2],
            [$pAxr2ma->id, 'Weighing Performance', 'Minimum Weight (USP, 0.1%, typical)', '1 mg', 3],
            [$pAxr2ma->id, 'Classification', 'Series', 'AXR', 4],
            [$pAxr2ma->id, 'Classification', 'Type', 'Microbalances', 5],
            [$pAxr2ma->id, 'Features', 'Display', 'Colour TFT Touchscreen', 6],
            [$pAxr2ma->id, 'Features', 'Calibration', 'Automatic Motorised Internal Calibration (PSAC )', 7],
            [$pAxr2ma->id, 'Classification', 'Class', 'Premium ($$$$)', 8],
            [$pAxr2ma->id, 'Weighing Performance', 'Maximum Capacity', '2.1 g', 10],
            [$pAxr2ma->id, 'Weighing Performance', 'Minimum load (OIML)', '0.1 mg', 11],
            [$pAxr2ma->id, 'Weighing Performance', 'Verification unit [e]', '1 mg', 12],
            [$pAxr2ma->id, 'Weighing Performance', 'Repeatability', '1 µg', 13],
            [$pAxr2ma->id, 'Weighing Performance', 'Linearity', '3 µg', 14],
            [$pAxr2ma->id, 'Weighing Performance', 'Tare Range (Subtractive)', '-2.1 g', 15],
            [$pAxr2ma->id, 'Weighing Performance', 'Standard repeatability [5% Max]', '0.5 µg', 16],
            [$pAxr2ma->id, 'Weighing Performance', 'Minimum Weight USP', '1 mg', 17],
            [$pAxr2ma->id, 'Weighing Performance', 'Minimum weight ((U=1%, k=2)', '0.1 mg', 18],
            [$pAxr2ma->id, 'Physical Specifications', 'Weighing Pan Diameter', 'Ø 26 mm', 19],
            [$pAxr2ma->id, 'Physical Specifications', 'Setting Time', '8-10 Sec', 20],
            [$pAxr2ma->id, 'System & Interface', 'User Management', "Log History [21 CFR Part 11 Compliant]\n7 User management\nPassword protection\nAuditTrail\nMinimum Sample Weight (MSW)", 21],
            [$pAxr2ma->id, 'System & Interface', 'Features', "Touch-free operation Infrared Proximity Sensor [Automatic Door]\n4 built-in languages English, German, French, Spanish\nLevelling guidance", 22],
            [$pAxr2ma->id, 'System & Interface', 'Units of measure', 'gm, kg, lb, oz, ozt, GN, dwt, ct, mg, mom, m, tlH1, tlH2, tlS, tlt1, tlt2, b, mg1, mg2', 23],
            [$pAxr2ma->id, 'Environmental Specifications', 'Operating temperature', '15º to 35ºC', 24],
            [$pAxr2ma->id, 'Environmental Specifications', 'Relative humidity', '40% ÷ 80%', 25],
            [$pAxr2ma->id, 'Environmental Specifications', 'Sensitivity Drift', '± 1ppm (1 x 10-6 / ºC)', 26],
            [$pAxr2ma->id, 'Physical Specifications', 'Protection class', 'IP 43', 27],
            [$pAxr2ma->id, 'Physical Specifications', 'Leveling system', 'Manual', 28],
            [$pAxr2ma->id, 'System & Interface', 'Interfaces', 'RS232/USB', 29],
            [$pAxr2ma->id, 'System & Interface', 'Built-in Application', 'Simple weighing, Counting, %percentage weighting, Custom Unit, Dynamic (Animal Weighing), Check weighting, Formulation, Totalization, Density, Statistics, Peak Hold', 30],
            [$pAxr2ma->id, 'Power & Compliance', 'Power Supply', 'DC Adapter, input 100 ~ 240V 0.5A output 13V / 0.9A', 31],
            [$pAxr2ma->id, 'Power & Compliance', 'Compliance', 'ISO, GLP, GMP, USP', 32],
            [$pAxr2ma->id, 'Dimensions & Weight', 'Dimensions for Electronic Box (W x D x H)', '520 x 360 x 560 mm', 33],
            [$pAxr2ma->id, 'Dimensions & Weight', 'Dimensions for Mechanical Box (W x D x H)', '520 x 360 x 560 mm', 34],
            [$pAxr2ma->id, 'Dimensions & Weight', 'Packaging dimensions (W x D x H)', '620 x 460 x 680 mm', 35],
            [$pAxr2ma->id, 'Dimensions & Weight', 'Net weight', '6.5 kg', 36],
            [$pAxr2ma->id, 'Dimensions & Weight', 'Gross weight', '9.5 kg', 37],

            [$pAxr2mf->id, 'Weighing Performance', 'Weighing Capacity', '2.1 g', 1],
            [$pAxr2mf->id, 'Weighing Performance', 'Readability', '1 µg', 2],
            [$pAxr2mf->id, 'Weighing Performance', 'Minimum Weight (USP, 0.1%, typical)', '1 mg', 3],
            [$pAxr2mf->id, 'Classification', 'Series', 'AXR', 4],
            [$pAxr2mf->id, 'Classification', 'Type', 'Filter Microbalances', 5],
            [$pAxr2mf->id, 'Features', 'Display', 'Colour TFT Touchscreen', 6],
            [$pAxr2mf->id, 'Features', 'Calibration', 'Automatic Motorised Internal Calibration (PSAC )', 7],
            [$pAxr2mf->id, 'Classification', 'Class', 'Premium ($$$$)', 8],
            [$pAxr2mf->id, 'Weighing Performance', 'Maximum Capacity', '2.1 g', 10],
            [$pAxr2mf->id, 'Weighing Performance', 'Minimum load (OIML)', '0.1 mg', 11],
            [$pAxr2mf->id, 'Weighing Performance', 'Verification unit [e]', '1 mg', 12],
            [$pAxr2mf->id, 'Weighing Performance', 'Repeatability', '1 µg', 13],
            [$pAxr2mf->id, 'Weighing Performance', 'Linearity', '3 µg', 14],
            [$pAxr2mf->id, 'Weighing Performance', 'Tare Range (Subtractive)', '-2.1 g', 15],
            [$pAxr2mf->id, 'Weighing Performance', 'Standard repeatability [5% Max]', '0.5 µg', 16],
            [$pAxr2mf->id, 'Weighing Performance', 'Minimum Weight USP', '1 mg', 17],
            [$pAxr2mf->id, 'Weighing Performance', 'Minimum weight ((U=1%, k=2)', '0.1 mg', 18],
            [$pAxr2mf->id, 'Physical Specifications', 'Weighing Pan Diameter', 'Ø 50 mm & Optional Ø 70 mm', 19],
            [$pAxr2mf->id, 'Physical Specifications', 'Setting Time', '8-10 Sec', 20],
            [$pAxr2mf->id, 'System & Interface', 'User Management', "Log History [21 CFR Part 11 Compliant]\n7 User management\nPassword protection\nAuditTrail\nMinimum Sample Weight (MSW)", 21],
            [$pAxr2mf->id, 'System & Interface', 'Features', "Manual Door\n4 built-in languages English, German, French, Spanish\nLevelling guidance", 22],
            [$pAxr2mf->id, 'System & Interface', 'Units of measure', 'gm, kg, lb, oz, ozt, GN, dwt, ct, mg, mom, m, tlH1, tlH2, tlS, tlt1, tlt2, b, mg1, mg2', 23],
            [$pAxr2mf->id, 'Environmental Specifications', 'Operating temperature', '15º to 35ºC', 24],
            [$pAxr2mf->id, 'Environmental Specifications', 'Relative humidity', '40% ÷ 80%', 25],
            [$pAxr2mf->id, 'Environmental Specifications', 'Sensitivity Drift', '± 1ppm (1 x 10-6 / ºC)', 26],
            [$pAxr2mf->id, 'Physical Specifications', 'Protection class', 'IP 43', 27],
            [$pAxr2mf->id, 'Physical Specifications', 'Leveling system', 'Manual', 28],
            [$pAxr2mf->id, 'System & Interface', 'Interfaces', 'RS232/USB', 29],
            [$pAxr2mf->id, 'System & Interface', 'Built-in Application', 'Simple weighing, Counting, %percentage weighting, Custom Unit, Dynamic (Animal Weighing), Check weighting, Formulation, Totalization, Density, Statistics, Peak Hold', 30],
            [$pAxr2mf->id, 'Power & Compliance', 'Power Supply', 'DC Adapter, input 100 ~ 240V 0.5A output 13V / 0.9A', 31],
            [$pAxr2mf->id, 'Power & Compliance', 'Compliance', 'ISO, GLP / GMP & USP', 32],
            [$pAxr2mf->id, 'Dimensions & Weight', 'Dimensions for Electronic Box (W x D x H)', '520 x 360 x 560 mm', 33],
            [$pAxr2mf->id, 'Dimensions & Weight', 'Dimensions for Mechanical Box (W x D x H)', '520 x 360 x 560 mm', 34],
            [$pAxr2mf->id, 'Dimensions & Weight', 'Packaging dimensions (W x D x H)', '620 x 460 x 680 mm', 35],
            [$pAxr2mf->id, 'Dimensions & Weight', 'Net weight', '6.5 kg', 36],
            [$pAxr2mf->id, 'Dimensions & Weight', 'Gross weight', '9.5 kg', 37],

            [$p3->id, 'Heating & Measurement', 'Max Sample Capacity', '200 g', 1],
            [$p3->id, 'Heating & Measurement', 'Moisture Readability', '0.01% / 0.001 g', 2],
            [$p3->id, 'Heating & Measurement', 'Temperature Range', '40°C to 200°C (1°C increments)', 3],
            [$p3->id, 'Heating & Measurement', 'Heat Source', '400W Halogen Lamp', 4],

            [$p6->id, 'Weighing & Sorting Performance', 'Sorting Speed', 'Up to 120 pcs/min', 1],
        ];

        foreach ($specs as $sp) {
            ProductSpecification::create([
                'product_id' => $sp[0],
                'group_name' => $sp[1],
                'spec_key' => $sp[2],
                'spec_value' => $sp[3],
                'sort_order' => $sp[4],
            ]);
        }

        // FAQs for AI Optimization
        $faqs = [
            [$p1->id, 'What calibration method does the Aczet CY 224 use?', 'The CY 224 features automatic motorized internal calibration (isoCAL) that triggers automatically when ambient temperature changes exceed preset thresholds.'],
            [$p1->id, 'Is the CY 224 balance compliant with GLP and GMP requirements?', 'Yes, it provides complete GLP/GMP compliant printouts via USB or RS232 including date, time, balance ID, and calibration status.'],
            [$p2->id, 'What is the resolution of the CM 21 ultra micro balance?', 'The CM 21 has a resolution of 1 microgram (0.000001g) with a maximum weighing capacity of 21 grams.'],
            [$p3->id, 'Can the MB 200 moisture analyzer store custom drying programs?', 'Yes, it includes internal memory for up to 100 user-defined drying programs with customized temperature curves.'],
        ];

        foreach ($faqs as $fq) {
            ProductFaq::create([
                'product_id' => $fq[0],
                'question' => $fq[1],
                'answer' => $fq[2],
            ]);
        }

        // Region Visibility
        $regions = ['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'];
        $allProducts = Product::all();

        foreach ($allProducts as $prod) {
            foreach ($regions as $reg) {
                // Example: CM 21 micro balance only visible in USA, Europe, GCC, India
                $visible = true;
                if ($prod->model_number === 'IS-600S' && $reg === 'USA') {
                    $visible = false; // Hide platform scale in USA region for demonstration
                }
                ProductRegionVisibility::create([
                    'product_id' => $prod->id,
                    'region' => $reg,
                    'is_visible' => $visible,
                ]);
            }
        }

        // Exhibitions
        Exhibition::create([
            'title' => 'LabIndia International Trade Fair 2026',
            'location' => 'BICC Mumbai, India',
            'hall_booth' => 'Hall 3, Stand B-42',
            'event_date' => '2026-10-15',
            'description' => 'Aczet showcases next generation ultra-micro balances and automated weighing solutions for pharmaceutical labs.',
            'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80',
            'is_upcoming' => true,
        ]);

        Exhibition::create([
            'title' => 'Analytica China 2026',
            'location' => 'SNIEC Shanghai, China',
            'hall_booth' => 'Hall E2, Stand 2410',
            'event_date' => '2026-11-18',
            'description' => 'Global analytical trade exhibition showcasing precision instruments, moisture determination, and material analysis tools.',
            'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
            'is_upcoming' => true,
        ]);

        // Blogs
        Blog::create([
            'title' => 'Understanding Calibration Standards in Pharmaceutical Balances',
            'slug' => 'calibration-standards-in-pharmaceutical-balances',
            'summary' => 'Explore the critical role of internal motorized calibration and USP Chapter 41 compliance in analytical laboratory weighing.',
            'content' => 'In pharmaceutical manufacturing, accurate weighing is non-negotiable. USP 41 dictates strict limits on minimum sample weights and repeatability...',
            'author' => 'Dr. R. Sharma (Metrology Division)',
            'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80',
            'published_at' => now()->subDays(5),
        ]);

        Blog::create([
            'title' => 'How Halogen Moisture Analyzers Accelerate Quality Control in Food Processing',
            'slug' => 'halogen-moisture-analyzers-quality-control',
            'summary' => 'Learn how switching from conventional drying ovens to halogen moisture analyzers reduces test time from hours to minutes.',
            'content' => 'Moisture content directly impacts shelf life, texture, and regulatory compliance in food manufacturing. Halogen heating provides uniform heat distribution...',
            'author' => 'Aczet QC Research Team',
            'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
            'published_at' => now()->subDays(12),
        ]);

        // Careers
        Career::create([
            'title' => 'Senior Precision Metrology Engineer',
            'department' => 'Research & Development',
            'location' => 'Mumbai Head Office',
            'type' => 'Full-Time',
            'experience' => '5+ Years',
            'description' => 'We are seeking an experienced Metrology Engineer to lead design and calibration algorithm development for micro-weighing load cells.',
            'is_active' => true,
        ]);

        Career::create([
            'title' => 'International Sales Executive (GCC & Europe Region)',
            'department' => 'Global Business Development',
            'location' => 'Dubai / Remote',
            'type' => 'Full-Time',
            'experience' => '3+ Years in B2B Scientific Equipment',
            'description' => 'Expand Aczet distributor networks and manage high-value B2B OEM accounts across GCC and European industrial sectors.',
            'is_active' => true,
        ]);

        // Sample Leads
        Lead::create([
            'name' => 'Michael Vance',
            'company' => 'BioPharma Solutions Corp',
            'email' => 'mvance@biopharmacorp.com',
            'phone' => '+1 415 890 1234',
            'product_id' => $p1->id,
            'region' => 'USA',
            'source' => 'Product Detail Quote Button',
            'status' => 'New',
            'message' => 'Looking to request official quote for 5 units of CY 224 balance with IQ/OQ validation protocols.',
        ]);

        Lead::create([
            'name' => 'Rajesh Kumar',
            'company' => 'Sun Pharmaceutical Industries',
            'email' => 'rkumar@sunpharma.example.com',
            'phone' => '+91 98200 12345',
            'product_id' => $p2->id,
            'region' => 'India',
            'source' => 'Sticky Quote Form',
            'status' => 'In Progress',
            'message' => 'Need technical demo of CM 21 ultra micro balance at our Vadodara R&D center.',
        ]);

        // Sample Dealer Request
        Dealer::create([
            'company_name' => 'Al-Maktoum Scientific Equipment LLC',
            'contact_person' => 'Tariq Al-Mansoor',
            'email' => 'tariq@almaktoum-scientific.ae',
            'phone' => '+971 4 390 5678',
            'country' => 'United Arab Emirates',
            'address' => 'Suite 402, Business Bay, Dubai',
            'status' => 'Approved',
        ]);

        // Sample Service Ticket
        ServiceRegistration::create([
            'ticket_number' => 'SR-2026-8891',
            'customer_name' => 'Dr. Elizabeth Warren',
            'email' => 'ewarren@labtech-global.com',
            'phone' => '+44 20 7946 0912',
            'product_name' => 'Aczet MB 200 Moisture Analyzer',
            'serial_number' => 'ACZ-MB200-994812',
            'purchase_date' => '2025-06-15',
            'issue_description' => 'Halogen lamp error code E-04 during fast heating cycle.',
            'status' => 'In Inspection',
        ]);

        $this->call(FaqSeeder::class);
    }
}
