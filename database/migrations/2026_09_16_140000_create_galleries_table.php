<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('General');
            $table->string('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial gallery items
        $initialItems = [
            [
                'title' => 'Ultra-Clean Assembly Facility',
                'category' => 'Manufacturing',
                'image' => '/images/products/cy224.svg',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laser Interferometer Metrology Lab',
                'category' => 'Testing',
                'image' => '/images/products/cm21.svg',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Halogen Thermal Analysis Suite',
                'category' => 'R&D',
                'image' => '/images/products/mb200.svg',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Heavy Load Cell Load Testing Bay',
                'category' => 'Industrial',
                'image' => '/images/products/is600.svg',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Gemstone Density Assaying Station',
                'category' => 'Jewelry',
                'image' => '/images/products/gxz600.svg',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Global Metrology Exhibition Hall',
                'category' => 'Exhibition',
                'image' => '/images/products/cy224.svg',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('galleries')->insert($initialItems);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};

