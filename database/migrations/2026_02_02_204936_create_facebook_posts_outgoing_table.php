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
        Schema::create('facebook_posts_outgoing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Admin who created
            
            // Post content
            $table->text('message')->nullable();
            $table->string('image')->nullable(); // Local image path
            $table->enum('post_type', ['text', 'photo', 'link'])->default('text');
            $table->string('link_url')->nullable(); // For link posts
            
            // Facebook response
            $table->string('facebook_post_id')->nullable()->index();
            $table->string('facebook_permalink')->nullable();
            
            // Status tracking
            $table->enum('status', ['draft', 'pending', 'publishing', 'published', 'failed'])->default('draft');
            $table->text('error_message')->nullable();
            $table->unsignedTinyInteger('retry_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable(); // For future: scheduled posts
            
            $table->timestamps();
            
            $table->index(['shop_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_posts_outgoing');
    }
};
