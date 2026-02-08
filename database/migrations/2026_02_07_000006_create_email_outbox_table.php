<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_outboxes', function (Blueprint $table) {
            $table->id();
            $table->json('to')->nullable();
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('from')->nullable();
            $table->string('subject')->nullable();
            $table->string('message_id')->nullable()->index();
            $table->string('transport')->nullable();
            $table->string('mailable')->nullable()->index();
            $table->longText('html_body')->nullable();
            $table->longText('text_body')->nullable();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();

            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_outboxes');
    }
};
