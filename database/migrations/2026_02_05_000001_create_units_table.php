<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('unit_number')->nullable();
            $table->foreignId('floor_id')->nullable()->constrained('floors')->nullOnDelete();
            $table->decimal('area', 10, 2)->nullable(); // sqm
            $table->decimal('price', 12, 2)->nullable();
            $table->string('price_type')->default('sale'); // sale, rent
            $table->string('currency', 10)->default('EGP');
            $table->string('status')->default('available'); // available, reserved, sold, rented
            $table->string('type')->default('shop'); // shop, office, kiosk, storage
            $table->string('image')->nullable();
            $table->text('features_ar')->nullable();
            $table->text('features_en')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
