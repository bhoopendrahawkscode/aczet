<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('translations')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('model_number')->nullable();
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('pdf_datasheet')->nullable();
            $table->string('pdf_manual')->nullable();
            $table->string('pdf_brochure')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('group_name')->default('General');
            $table->string('spec_key');
            $table->string('spec_value');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_region_visibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('region'); // 'India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('region')->default('Global');
            $table->string('source')->default('Organic'); // Organic, Paid, Direct, Campaign, Sticky Quote, Demo
            $table->string('status')->default('New'); // New, Contacted, In Progress, Closed
            $table->text('message')->nullable();
            $table->json('raw_meta')->nullable();
            $table->timestamps();
        });

        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('country');
            $table->text('address')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->string('tier')->nullable()->default('Tier 1 Gold Partner');
            $table->decimal('discount_percentage', 5, 2)->default(35.00);
            $table->string('account_manager')->nullable();
            $table->timestamps();
        });

        Schema::create('exhibitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('hall_booth')->nullable();
            $table->date('event_date');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_upcoming')->default(true);
            $table->timestamps();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('author')->default('Aczet Team');
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('department');
            $table->string('location');
            $table->string('type')->default('Full-Time');
            $table->string('experience')->nullable();
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');
            $table->string('product_name');
            $table->string('serial_number');
            $table->date('purchase_date')->nullable();
            $table->text('issue_description');
            $table->string('status')->default('Received'); // Received, In Inspection, Resolved
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_registrations');
        Schema::dropIfExists('careers');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('exhibitions');
        Schema::dropIfExists('dealers');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('product_region_visibilities');
        Schema::dropIfExists('product_faqs');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
