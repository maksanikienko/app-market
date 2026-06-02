<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('brand_id')->unsigned()->nullable()->after('category_id');
            $table->string('slug')->nullable()->after('name'); // unique added after populating
            $table->string('code')->unique()->change();

            $table->string('short_description')->nullable()->after('description');

            $table->decimal('price', 12, 2)->change();
            $table->decimal('old_price', 12, 2)->nullable()->after('price');

            $table->boolean('is_active')->default(true)->after('old_price');
            $table->boolean('is_new')->default(false);
            $table->boolean('is_hit')->default(false);
            $table->boolean('is_sale')->default(false);

            // Classifiers
            $table->foreignId('outer_material_id')->nullable()
                ->constrained('product_classifiers')
                ->onDelete('set null');

            $table->foreignId('lining_material_id')->nullable()
                ->constrained('product_classifiers')
                ->onDelete('set null');

            $table->foreignId('filling_id')->nullable()
                ->constrained('product_classifiers')
                ->onDelete('set null');

            $table->string('length')->nullable()->comment('Length type: Short, Medium, Long');
            $table->boolean('hood')->default(false)->comment('Has hood');
            $table->boolean('detachable_hood')->default(false)->comment('Hood is detachable');
            $table->boolean('waterproof')->default(false)->comment('Has Waterproof');

            $table->string('season')->nullable()->comment('Season: Winter, Demi-season etc.');

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
        });

        DB::table('products')->get()->each(function ($product) {
            DB::table('products')->where('id', $product->id)->update([
                'slug' => Str::slug($product->name) . '-' . $product->id,
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['outer_material_id']);
            $table->dropForeign(['lining_material_id']);
            $table->dropForeign(['filling_id']);

            $table->dropColumn([
                'brand_id', 'slug', 'short_description', 'old_price',
                'is_active', 'is_new', 'is_hit', 'is_sale',
                'outer_material_id', 'lining_material_id', 'filling_id',
                'length', 'hood', 'detachable_hood', 'waterproof', 'season'
            ]);
        });
    }
};