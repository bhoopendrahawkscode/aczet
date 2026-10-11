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
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('type')->default('Blog')->after('slug');
        });

        // Ensure all existing blogs have type 'Blog'
        DB::table('blogs')->whereNull('type')->orWhere('type', '')->update(['type' => 'Blog']);

        // Seed a sample News item
        DB::table('blogs')->insert([
            'title' => 'Aczet Showcases Next-Gen Micro-Balances at Analytica Global Expo',
            'slug' => 'aczet-showcases-next-gen-micro-balances-at-analytica',
            'type' => 'News',
            'summary' => 'Aczet announces the commercial rollout of its CM Series ultra-micro analytical balances featuring sub-microgram readability and ISO 17025 compliance.',
            'content' => 'Aczet officially launched its latest generation of high-precision laboratory balances today. Designed for advanced metrology, materials science, and pharmaceutical assaying, the new CM Series brings unprecedented stability and temperature compensation algorithms. Aczet executives emphasized the company\'s continued dedication to ISO compliance and global calibration standards.',
            'author' => 'Aczet Corporate Newsroom',
            'image' => 'https://images.unsplash.com/photo-1507668077129-56e32842fceb?auto=format&fit=crop&w=800&q=80',
            'published_at' => now()->subDays(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

