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
        Schema::create('mall_shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('floor')->nullable();
            $table->integer('number')->default(0);
            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();
            $table->string('owner_id_number')->nullable();
            $table->string('tenant_name')->nullable();
            $table->string('tenant_phone')->nullable();
            $table->string('tenant_id_number')->nullable();
            $table->decimal('sale_value', 12, 2)->default(0);
            $table->decimal('rent_value', 12, 2)->default(0);
            $table->integer('lease_years')->default(0);
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['floor', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mall_shops');
    }
};
