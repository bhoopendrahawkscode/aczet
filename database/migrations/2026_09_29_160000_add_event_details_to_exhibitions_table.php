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
        Schema::table('exhibitions', function (Blueprint $table) {
            if (!Schema::hasColumn('exhibitions', 'event_type')) {
                $table->string('event_type')->nullable()->after('title');
            }
            if (!Schema::hasColumn('exhibitions', 'date_range')) {
                $table->string('date_range')->nullable()->after('event_date');
            }
            if (!Schema::hasColumn('exhibitions', 'is_virtual')) {
                $table->boolean('is_virtual')->default(false)->after('is_upcoming');
            }
            if (!Schema::hasColumn('exhibitions', 'has_more_dates')) {
                $table->boolean('has_more_dates')->default(false)->after('is_virtual');
            }
            if (!Schema::hasColumn('exhibitions', 'link')) {
                $table->string('link')->nullable()->after('has_more_dates');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitions', function (Blueprint $table) {
            $cols = [];
            foreach (['event_type', 'date_range', 'is_virtual', 'has_more_dates', 'link'] as $c) {
                if (Schema::hasColumn('exhibitions', $c)) {
                    $cols[] = $c;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
