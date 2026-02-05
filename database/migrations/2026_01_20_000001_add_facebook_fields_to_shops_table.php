<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('facebook_page_id')->nullable();
            $table->text('facebook_page_access_token')->nullable();
            $table->index('facebook_page_id');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropIndex(['facebook_page_id']);
            $table->dropColumn(['facebook_page_id', 'facebook_page_access_token']);
        });
    }
};
