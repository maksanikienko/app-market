<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_classifiers', function (Blueprint $table) {
            $table->id();

            $table->string('type')->comment('Type of classifier: outer_material, lining_material, filling, season, length');
            $table->string('key')->comment('Unique machine key for translations (polyester, goose_down, winter...)');
            $table->string('name')->comment('Default display name');
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Уникальность в рамках типа
            $table->unique(['type', 'key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_classifiers');
    }
};