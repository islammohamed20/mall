<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            // Cart settings
            $table->boolean('cart_enabled')->default(true)->after('is_open_now');
            $table->integer('cart_min_order_amount')->default(0)->after('cart_enabled');
            $table->integer('cart_max_items')->default(100)->after('cart_min_order_amount');
            $table->boolean('cart_allow_gift_message')->default(true)->after('cart_max_items');
            $table->boolean('cart_allow_coupon')->default(true)->after('cart_allow_gift_message');
            $table->integer('cart_abandoned_timeout_minutes')->default(60)->after('cart_allow_coupon');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'cart_enabled',
                'cart_min_order_amount',
                'cart_max_items',
                'cart_allow_gift_message',
                'cart_allow_coupon',
                'cart_abandoned_timeout_minutes',
            ]);
        });
    }
};
