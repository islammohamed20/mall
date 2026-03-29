<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckoutOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'payment_type',
        'name',
        'email',
        'phone',
        'shipping_address',
        'shipping_city',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'notes',
        'cart_snapshot',
        'subtotal',
        'total',
        'currency',
        'status',
    ];

    protected $casts = [
        'cart_snapshot' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
