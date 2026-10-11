<!-- ============================================================== -->
<!-- ACZET FLOATING ACTIONS DOCK (AI CHATBOT & DIRECT WHATSAPP)    -->
<!-- ============================================================== -->
<div class="aczet-floating-widgets sticky-actions-container" id="aczetFloatingWidgets" aria-label="Quick Communication Dock">

    <!-- 1. Direct WhatsApp Messaging Option -->
    <a href="https://wa.me/919876543210?text={{ urlencode('Hello Aczet Team, I am visiting your website and would like to inquire about your precision balances and metrology equipment.') }}" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="floating-action-pill floating-whatsapp-btn sticky-whatsapp-btn" 
       id="floatingWhatsAppBtn"
       title="Start WhatsApp conversation with Aczet"
       aria-label="Direct WhatsApp conversation with Aczet">
        <span class="floating-pill-icon"><i class="fa-brands fa-whatsapp"></i></span>
        <span class="pill-label">WhatsApp</span>
    </a>

    <!-- 2. AI Chatbot Floating Trigger -->
    <button type="button" 
            class="floating-action-pill floating-ai-btn" 
            id="floatingAiChatBtn" 
            onclick="toggleAiChat()" 
            title="Ask Aczet AI Assistant"
            aria-label="Open Aczet AI Metrology Chatbot"
            aria-expanded="false" 
            aria-controls="aiChatWindow">
        <span class="floating-pill-icon"><i class="fa-solid fa-robot"></i></span>
        <span class="pill-label">Ask AI Assistant</span>
        <span class="ai-chip-badge">AI</span>
    </button>

    <!-- 3. Instant Quote / Enquiry Trigger -->
    {{-- <button type="button" 
            class="floating-action-pill floating-quote-btn sticky-enquiry-btn" 
            id="floatingQuoteBtn"
            onclick="openQuoteModal('Floating Action Sticky Button', null)" 
            title="Request an Instant Quote"
            aria-label="Request Instant Quote">
        <span class="floating-pill-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
        <span class="pill-label">Instant Quote</span>
    </button> --}}

</div>

<!-- ============================================================== -->
<!-- AI CHATBOT INTERACTIVE WINDOW                                 -->
<!-- ============================================================== -->
<div class="ai-chat-window" id="aiChatWindow" role="dialog" aria-modal="true" aria-labelledby="aiChatTitle" aria-hidden="true">
    <!-- Header -->
    <div class="ai-chat-header">
        <div class="ai-chat-title-wrap">
            <div class="ai-avatar-icon">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div>
                <h4 id="aiChatTitle">Aczet AI Assistant</h4>
                <span class="ai-status-indicator">
                    <span class="status-dot"></span> Online &bull; Instant Assistance
                </span>
            </div>
        </div>
        <button type="button" class="ai-chat-close-btn" onclick="toggleAiChat(false)" aria-label="Close AI Chat">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Body / Conversation Stream -->
    <div class="ai-chat-body" id="aiChatMessages" tabindex="0">
        <!-- Initial Bot Greeting -->
        <div class="ai-msg ai-msg-bot">
            <div class="ai-msg-bubble">
                <p style="margin: 0 0 8px;">Hello! 👋 Welcome to <strong>Aczet Metrology</strong>. I am your AI assistant.</p>
                <p style="margin: 0;">How can I help you today? You can ask about our laboratory balances, moisture analyzers, calibration certificates, request pricing, or start a WhatsApp chat with our team.</p>
            </div>
            <span class="ai-msg-time">Just now</span>
        </div>

        <!-- Quick Prompt Chips -->
        <div class="ai-quick-prompts" id="aiQuickPrompts">
            <button type="button" class="ai-quick-chip" onclick="handleAiQuickPrompt('Laboratory Balances')">
                <i class="fa-solid fa-scale-balanced"></i> Lab Balances
            </button>
            <button type="button" class="ai-quick-chip" onclick="handleAiQuickPrompt('Moisture Analyzers')">
                <i class="fa-solid fa-droplet"></i> Moisture Analyzers
            </button>
            <button type="button" class="ai-quick-chip" onclick="handleAiQuickPrompt('Request a Quote')">
                <i class="fa-solid fa-file-invoice"></i> Get a Quote
            </button>
            <button type="button" class="ai-quick-chip" onclick="handleAiQuickPrompt('Calibration and Service')">
                <i class="fa-solid fa-certificate"></i> ISO Calibration
            </button>
            <button type="button" class="ai-quick-chip" onclick="handleAiQuickPrompt('Chat on WhatsApp')">
                <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
            </button>
        </div>
    </div>

    <!-- Footer / Input Form -->
    <div class="ai-chat-footer">
        <form id="aiChatForm" onsubmit="submitAiMessage(event)">
            <input type="text" 
                   id="aiChatInput" 
                   placeholder="Ask about products, specs, quotes..." 
                   autocomplete="off" 
                   aria-label="Type your message for Aczet AI">
            <button type="submit" id="aiChatSubmitBtn" aria-label="Send message">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
    // AI Chatbot Visibility Toggle
    function toggleAiChat(forceState) {
        const chatWindow = document.getElementById('aiChatWindow');
        const triggerBtn = document.getElementById('floatingAiChatBtn');
        if (!chatWindow) return;

        const willOpen = (typeof forceState === 'boolean') ? forceState : !chatWindow.classList.contains('open');

        if (willOpen) {
            chatWindow.classList.add('open');
            chatWindow.setAttribute('aria-hidden', 'false');
            if (triggerBtn) triggerBtn.setAttribute('aria-expanded', 'true');
            const input = document.getElementById('aiChatInput');
            if (input) setTimeout(() => input.focus(), 150);
        } else {
            chatWindow.classList.remove('open');
            chatWindow.setAttribute('aria-hidden', 'true');
            if (triggerBtn) triggerBtn.setAttribute('aria-expanded', 'false');
        }
    }

    // Quick Prompt Handler
    function handleAiQuickPrompt(promptText) {
        const input = document.getElementById('aiChatInput');
        if (input) {
            input.value = promptText;
            submitAiMessage(new Event('submit'));
        }
    }

    // AI Message Submission
    function submitAiMessage(e) {
        if (e && e.preventDefault) e.preventDefault();
        const input = document.getElementById('aiChatInput');
        if (!input) return;

        const text = input.value.trim();
        if (!text) return;

        appendAiMessage(text, 'user');
        input.value = '';

        // Show typing indicator
        const typingEl = showAiTypingIndicator();

        // Simulate intelligent response after short delay
        setTimeout(() => {
            if (typingEl && typingEl.parentNode) typingEl.remove();
            const reply = generateAiResponse(text);
            appendAiMessage(reply, 'bot');
        }, 550);
    }

    // Append Message to Stream
    function appendAiMessage(content, sender) {
        const container = document.getElementById('aiChatMessages');
        if (!container) return;

        const now = new Date();
        const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        const msgDiv = document.createElement('div');
        msgDiv.className = `ai-msg ai-msg-${sender}`;

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'ai-msg-bubble';

        if (typeof content === 'string') {
            bubbleDiv.innerHTML = content;
        } else {
            bubbleDiv.appendChild(content);
        }

        const timeSpan = document.createElement('span');
        timeSpan.className = 'ai-msg-time';
        timeSpan.textContent = timeStr;

        msgDiv.appendChild(bubbleDiv);
        msgDiv.appendChild(timeSpan);
        container.appendChild(msgDiv);

        // Auto-scroll to bottom
        container.scrollTop = container.scrollHeight;
    }

    // Typing Indicator Element
    function showAiTypingIndicator() {
        const container = document.getElementById('aiChatMessages');
        if (!container) return null;

        const typingDiv = document.createElement('div');
        typingDiv.className = 'ai-msg ai-msg-bot ai-typing-msg';
        typingDiv.innerHTML = `
            <div class="ai-typing-indicator">
                <span class="ai-typing-dot"></span>
                <span class="ai-typing-dot"></span>
                <span class="ai-typing-dot"></span>
            </div>
        `;
        container.appendChild(typingDiv);
        container.scrollTop = container.scrollHeight;
        return typingDiv;
    }

    // Aczet Domain Knowledge Engine
    function generateAiResponse(query) {
        const q = query.toLowerCase();

        // 1. WhatsApp inquiries
        if (q.includes('whatsapp') || q.includes('chat') && (q.includes('human') || q.includes('person') || q.includes('agent') || q.includes('direct'))) {
            return `You can chat directly with our technical sales engineers on WhatsApp! <br><br>
                <a href="https://wa.me/919876543210?text=${encodeURIComponent('Hello Aczet Team, I would like direct assistance regarding metrology equipment.')}" target="_blank" rel="noopener" class="ai-chat-link" style="font-weight:700; color:#16a34a;">
                    <i class="fa-brands fa-whatsapp"></i> Click to Open WhatsApp Chat
                </a><br><br>
                Our team responds quickly during business hours.`;
        }

        // 2. Price / Quotation / RFQ
        if (q.includes('quote') || q.includes('price') || q.includes('cost') || q.includes('pricing') || q.includes('rfq') || q.includes('buy') || q.includes('purchase')) {
            return `We offer customized quotations for institutional, industrial, and laboratory projects worldwide.<br><br>
                • <a href="javascript:void(0)" onclick="openQuoteModal('AI Chat Inquiry', null)" class="ai-chat-inline-btn"><i class="fa-solid fa-file-invoice"></i> Open Instant Quote Form</a><br>
                • Or reach our sales team instantly on <a href="https://wa.me/919876543210?text=${encodeURIComponent('Hello Aczet, I would like pricing details for precision equipment.')}" target="_blank" class="ai-chat-link"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>.`;
        }

        // 3. Laboratory Balances / Microbalance / Analytical / Precision
        if (q.includes('balance') || q.includes('microbalance') || q.includes('analytical') || q.includes('precision') || q.includes('scale') || q.includes('weighing') || q.includes('laboratory') || q.includes('lab')) {
            return `<strong>Aczet Laboratory Balances</strong> deliver world-class precision engineered in India &amp; Switzerland:<br><br>
                • <strong>Ultra-Micro &amp; Microbalances</strong>: Down to 0.000001 g (1 µg) readability<br>
                • <strong>Analytical Balances (AXR &amp; CY Series)</strong>: 0.1 mg / 0.01 mg with Monotech EMFC sensors<br>
                • <strong>Precision Balances</strong>: Capacities from 220 g up to 32 kg<br>
                • Features: Motorized internal calibration, USP Chapter 41 compliance, and 21 CFR Part 11 audit trails.<br><br>
                <a href="{{ route('products.index') }}" class="ai-chat-link">View All Balances</a> &bull; <a href="javascript:void(0)" onclick="openQuoteModal('AI Lab Balance Query', null)" class="ai-chat-inline-btn">Get Balance Quote</a>`;
        }

        // 4. Moisture Analyzers
        if (q.includes('moisture') || q.includes('mb') || q.includes('halogen') || q.includes('lod') || q.includes('loss on drying')) {
            return `<strong>Aczet MB Series Moisture Analyzers</strong> feature rapid halogen heating technology with 0.001g to 0.0001g readability.<br><br>
                Ideal for pharmaceutical, food processing, plastics, and chemical quality control with customizable drying profiles and memory storage.<br><br>
                <a href="{{ route('products.index', ['search' => 'Moisture']) }}" class="ai-chat-link">Explore Moisture Analyzers</a> &bull; <a href="javascript:void(0)" onclick="openQuoteModal('Moisture Analyzer Inquiry', null)" class="ai-chat-inline-btn">Request Quote</a>`;
        }

        // 5. Calibration / Service / Repair / Warranty / ISO 17025
        if (q.includes('calibration') || q.includes('service') || q.includes('repair') || q.includes('maintenance') || q.includes('amc') || q.includes('iso') || q.includes('certificate')) {
            return `Aczet operates an ISO/IEC 17025 accredited calibration laboratory providing certified traceability to national standards (NABL/PTB).<br><br>
                Need on-site maintenance or calibration?<br>
                • <a href="{{ route('service_registration') }}" class="ai-chat-link"><i class="fa-solid fa-wrench"></i> Submit Service Ticket</a><br>
                • Contact service engineers directly on <a href="https://wa.me/919876543210?text=${encodeURIComponent('Hello Aczet Service Team, I require calibration/service support.')}" target="_blank" class="ai-chat-link"><i class="fa-brands fa-whatsapp"></i> WhatsApp Service</a>.`;
        }

        // 6. Test Weights / Density
        if (q.includes('weight') || q.includes('test weight') || q.includes('density') || q.includes('e1') || q.includes('e2') || q.includes('f1')) {
            return `Aczet manufactures high-precision <strong>OIML R111-1 Class E1, E2, F1, F2 &amp; M1 stainless steel weights</strong> and <strong>Density Determination Kits</strong> for solid and liquid gravity measurement with ISO 17025 certificates.<br><br>
                <a href="{{ route('products.index') }}" class="ai-chat-link">View Metrology Accessories</a>.`;
        }

        // 7. Jewelry / XRF / Gold Testing
        if (q.includes('jewelry') || q.includes('gold') || q.includes('xrf') || q.includes('karat') || q.includes('laser')) {
            return `Aczet is a market leader in <strong>Jewelry Machinery &amp; XRF Gold Testing Analyzers</strong>. Our instruments offer non-destructive precious metal analysis in seconds with pin-point accuracy.<br><br>
                <a href="javascript:void(0)" onclick="openQuoteModal('XRF Gold Tester Inquiry', null)" class="ai-chat-inline-btn">Inquire for Jewelry Instruments</a>.`;
        }

        // 8. Contact / Offices / Location / Support
        if (q.includes('contact') || q.includes('location') || q.includes('office') || q.includes('address') || q.includes('headquarters') || q.includes('phone') || q.includes('email') || q.includes('mumbai') || q.includes('where')) {
            return `<strong>Aczet Corporate Headquarters:</strong><br>
                201, Commercial Center, Mumbai 400069, Maharashtra, India.<br>
                • <strong>Phone:</strong> +91 22 4141 5555<br>
                • <strong>Email:</strong> sales@aczet.com<br>
                • <strong>Global Hubs:</strong> Switzerland, USA, Dubai, Singapore, Thailand (85+ export countries).<br><br>
                <a href="{{ route('about.global_presence') }}" class="ai-chat-link">View Global Presence Map</a>`;
        }

        // 9. Comparison
        if (q.includes('compare') || q.includes('comparison') || q.includes('difference')) {
            return `You can compare up to 10 Aczet products side-by-side using our comparison tool.<br><br>
                Simply check the "Compare" box next to products in our catalog, or visit the <a href="{{ route('compare') }}" class="ai-chat-link">Comparison Tool</a>.`;
        }

        // 10. Greetings
        if (q.includes('hello') || q.includes('hi') || q.includes('hey') || q.includes('good morning') || q.includes('good afternoon')) {
            return `Hello! How can I assist you with Aczet precision weighing equipment today? Feel free to ask about our laboratory balances, moisture analyzers, quotations, or chat with our team on WhatsApp!`;
        }

        // Default Fallback
        return `Thank you for your question! I can help you with specifications, pricing, and applications for Aczet precision weighing instruments.<br><br>
            Would you like to:<br>
            • <a href="javascript:void(0)" onclick="openQuoteModal('General Inquiry', null)" class="ai-chat-inline-btn"><i class="fa-solid fa-file-invoice"></i> Request a Quote</a><br>
            • <a href="https://wa.me/919876543210?text=${encodeURIComponent('Hello Aczet Team, I would like more information on: ' + query)}" target="_blank" class="ai-chat-link"><i class="fa-brands fa-whatsapp"></i> Chat directly on WhatsApp</a><br>
            • <a href="{{ route('products.index') }}" class="ai-chat-link"><i class="fa-solid fa-magnifying-glass"></i> Explore Product Catalog</a>`;
    }
</script>
