<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->string('color')->nullable()->comment('Color name (e.g. Black, Beige, Red)');
            $table->string('color_hex')->nullable()->comment('Hex color code (#FF0000)');
            $table->string('size')->nullable()->comment('Size (XS, S, M, L, XL, 42, 44, etc.)');

            $table->string('sku')->unique()->nullable()->comment('Unique variant SKU');
            $table->decimal('price', 12, 2)->nullable()->comment('Override price for this variant');
            $table->integer('stock')->default(0)->comment('Stock quantity');

            $table->timestamps();

            $table->index(['product_id', 'color']);
            $table->index(['product_id', 'size']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};