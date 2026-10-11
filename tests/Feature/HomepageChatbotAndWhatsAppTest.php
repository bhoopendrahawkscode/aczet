<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageChatbotAndWhatsAppTest extends TestCase
{
    /**
     * Test that homepage renders the floating AI Chatbot and direct WhatsApp options.
     */
    public function test_homepage_renders_ai_chatbot_and_direct_whatsapp_widgets(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // 1. Unified Floating Dock
        $response->assertSee('aczetFloatingWidgets', false);
        $response->assertSee('aczet-floating-widgets', false);

        // 2. Direct WhatsApp Messaging Option
        $response->assertSee('floatingWhatsAppBtn', false);
        $response->assertSee('wa.me/919876543210', false);
        $response->assertSee('WhatsApp');
        $response->assertSee('fa-whatsapp', false);

        // 3. AI Chatbot Floating Trigger
        $response->assertSee('floatingAiChatBtn', false);
        $response->assertSee('Ask AI Assistant');
        $response->assertSee('ai-chip-badge', false);
        $response->assertSee('toggleAiChat()', false);

        // 4. AI Chatbot Interactive Window
        $response->assertSee('aiChatWindow', false);
        $response->assertSee('Aczet AI Assistant');
        $response->assertSee('Online &bull; Instant Assistance', false);
        $response->assertSee('aiChatMessages', false);
        $response->assertSee('aiQuickPrompts', false);
        $response->assertSee('Lab Balances');
        $response->assertSee('Moisture Analyzers');
        $response->assertSee('aiChatForm', false);
        $response->assertSee('aiChatInput', false);
        $response->assertSee('aiChatSubmitBtn', false);
    }
}
