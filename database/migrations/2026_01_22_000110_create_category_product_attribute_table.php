<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_product_attribute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_attribute_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['shop_category_id', 'product_attribute_id'], 'category_attribute_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product_attribute');
    }
};

