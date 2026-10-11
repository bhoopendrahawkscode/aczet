<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dealer;
use Illuminate\Support\Facades\Hash;

class CustomerAuthTest extends TestCase
{
    public function test_customer_auth_page_renders_login_and_signup_tabs(): void
    {
        $response = $this->get('/customer/login');
        $response->assertStatus(200);
        $response->assertSee('Customer Login');
        $response->assertSee('Sign Up');
        $response->assertSee('Precision Measurement &amp; Client Services', false);
        $response->assertSee('Track RFQs &amp; Orders', false);

        // Also check that /dealer/login renders customer auth
        $dealerResponse = $this->get('/dealer/login');
        $dealerResponse->assertStatus(200);
        $dealerResponse->assertSee('Customer Login');
        $dealerResponse->assertSee('Sign Up');
    }

    public function test_new_customer_can_register_and_is_redirected_to_dashboard(): void
    {
        $email = 'customer.' . uniqid() . '@example.com';

        $response = $this->post('/customer/register', [
            'name' => 'Dr. Robert Oppenheimer',
            'email' => $email,
            'phone' => '+1 555 123 4567',
            'company' => 'National Metrology Lab',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => 'Dr. Robert Oppenheimer',
            'company' => 'National Metrology Lab',
            'role' => 'customer',
        ]);
    }

    public function test_existing_customer_can_log_in_and_access_dashboard(): void
    {
        $email = 'client.' . uniqid() . '@pharmalab.com';
        $user = User::create([
            'name' => 'Dr. Jane Foster',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post('/customer/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertAuthenticatedAs($user);

        // Dashboard renders
        $dashResponse = $this->get('/customer/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Welcome, Dr. Jane Foster');
        $dashResponse->assertSee('Recent Quotation Inquiries &amp; RFQs', false);
    }

    public function test_customer_can_log_out(): void
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'logout.' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/customer/logout');
        $response->assertRedirect('/customer/login');
        $this->assertGuest();
    }

    public function test_header_renders_customer_login_link(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('/customer/login');
        $response->assertSee('Customer Login &amp; Sign Up', false);
    }
}

