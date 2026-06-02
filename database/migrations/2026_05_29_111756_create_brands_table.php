<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Brand name');
            $table->string('slug')->unique()->comment('URL-friendly slug');
            $table->string('logo')->nullable()->comment('Brand logo image path');
            $table->text('description')->nullable()->comment('Short brand description');
            $table->boolean('is_active')->default(true)->comment('Active status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('brands');
    }
};