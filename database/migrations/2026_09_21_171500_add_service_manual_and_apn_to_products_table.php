<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'pdf_service_manual')) {
                $table->string('pdf_service_manual')->nullable()->after('pdf_brochure');
            }
            if (!Schema::hasColumn('products', 'apn_number')) {
                $table->string('apn_number')->nullable()->after('model_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'pdf_service_manual')) {
                $table->dropColumn('pdf_service_manual');
            }
            if (Schema::hasColumn('products', 'apn_number')) {
                $table->dropColumn('apn_number');
            }
        });
    }
};

