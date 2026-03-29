<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('fb_post_id');
            $table->longText('message')->nullable();
            $table->text('permalink_url')->nullable();
            $table->text('image_url')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->string('status')->default('pending');
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->unique(['shop_id', 'fb_post_id']);
            $table->index(['shop_id', 'status']);
            $table->index('posted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_posts');
    }
};
