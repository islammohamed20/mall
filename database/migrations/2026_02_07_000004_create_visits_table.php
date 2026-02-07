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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->string('visitor_uid', 36)->index();
            $table->string('session_id', 100)->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('ip', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('path', 2048)->index();
            $table->string('referer', 2048)->nullable();

            $table->string('device_type', 20)->nullable()->index();
            $table->string('platform', 60)->nullable()->index();
            $table->string('browser', 60)->nullable()->index();

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->decimal('accuracy_m', 10, 2)->nullable();
            $table->string('geo_source', 20)->nullable()->index();
            $table->timestamp('geo_captured_at')->nullable();

            $table->timestamps();

            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
