<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            $table->string('color')->nullable()->after('variant_id');
            $table->string('color_hex', 20)->nullable()->after('color');
            $table->string('size', 20)->nullable()->after('color_hex');
        });
    }

    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn(['variant_id', 'color', 'color_hex', 'size']);
        });
    }
};