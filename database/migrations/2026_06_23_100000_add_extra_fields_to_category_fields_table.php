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
        Schema::table('category_fields', function (Blueprint $table) {
            $table->string('default_value')->nullable()->after('placeholder');
            $table->unsignedInteger('sort_order')->default(0)->after('default_value');
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->dropColumn(['default_value', 'sort_order', 'is_active']);
        });
    }
};
