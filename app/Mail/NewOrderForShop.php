<?php

namespace App\Mail;

use App\Models\CheckoutOrder;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderForShop extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    public function __construct(
        public CheckoutOrder $order,
        public Shop $shop,
        public array $items,
    ) {
    }

    public function build(): self
    {
        $subject = app()->getLocale() === 'ar'
            ? "طلب جديد للمحل: {$this->shop->name} (#{$this->order->id})"
            : "New order for {$this->shop->name} (#{$this->order->id})";

        return $this->subject($subject)
            ->view('emails.new-order-for-shop');
    }
}
