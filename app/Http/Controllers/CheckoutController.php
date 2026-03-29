<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderForShop;
use App\Models\CheckoutOrder;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $productIds = array_keys($cart);

        $products = Product::with(['shop', 'images'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $cartItems = collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get($productId);
            if (! $product) {
                return null;
            }

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->price ? (float) $product->price * $quantity : 0,
            ];
        })->filter()->values();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', app()->getLocale() === 'ar' ? 'سلتك فارغة.' : 'Your cart is empty.');
        }

        $total = $cartItems->sum('subtotal');
        $paymentMethods = PaymentMethod::query()->publicAvailable()->ordered()->get();

        if ($paymentMethods->isEmpty()) {
            return redirect()->route('cart.index')->with('error', app()->getLocale() === 'ar' ? 'لا توجد طرق دفع متاحة حالياً.' : 'No payment methods are currently available.');
        }

        return view('pages.checkout.create', compact('cartItems', 'total', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', app()->getLocale() === 'ar' ? 'سلتك فارغة.' : 'Your cart is empty.');
        }

        $paymentMethod = PaymentMethod::query()->publicAvailable()->whereKey($request->input('payment_method_id'))->first();
        if (! $paymentMethod) {
            return back()->with('error', app()->getLocale() === 'ar' ? 'يرجى اختيار طريقة دفع صحيحة.' : 'Please select a valid payment method.');
        }

        $baseRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        $cardRules = [
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:120'],
            'billing_name' => ['required', 'string', 'max:255'],
            'billing_email' => ['required', 'email', 'max:255'],
            'billing_phone' => ['required', 'string', 'max:50'],
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:120'],
        ];

        $codRules = [
            'shipping_address' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['nullable', 'string', 'max:120'],
            'billing_name' => ['nullable', 'string', 'max:255'],
            'billing_email' => ['nullable', 'email', 'max:255'],
            'billing_phone' => ['nullable', 'string', 'max:50'],
            'billing_address' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['nullable', 'string', 'max:120'],
        ];

        $rules = $paymentMethod->type === 'card'
            ? array_merge($baseRules, $cardRules)
            : array_merge($baseRules, $codRules);

        $data = $request->validate($rules);

        $products = Product::with(['shop'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $cartItems = collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get($productId);
            if (! $product) {
                return null;
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'currency' => $product->currency,
                'quantity' => (int) $quantity,
                'subtotal' => $product->price ? (float) $product->price * $quantity : 0,
                'shop' => $product->shop?->name,
                'shop_id' => $product->shop_id,
            ];
        })->filter()->values();

        $total = $cartItems->sum('subtotal');
        $currency = $cartItems->first()['currency'] ?? 'EGP';

        $user = User::query()->where('email', $data['email'])->first();
        if (! $user) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(12)),
            ]);
        }

        $order = CheckoutOrder::create([
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_type' => $paymentMethod->type,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'shipping_address' => $data['shipping_address'] ?? null,
            'shipping_city' => $data['shipping_city'] ?? null,
            'billing_name' => $data['billing_name'] ?? null,
            'billing_email' => $data['billing_email'] ?? null,
            'billing_phone' => $data['billing_phone'] ?? null,
            'billing_address' => $data['billing_address'] ?? null,
            'billing_city' => $data['billing_city'] ?? null,
            'notes' => $data['notes'] ?? null,
            'cart_snapshot' => $cartItems,
            'subtotal' => $total,
            'total' => $total,
            'currency' => $currency,
            'status' => 'pending',
        ]);

        $shopIds = collect($cartItems)
            ->pluck('shop_id')
            ->filter()
            ->unique()
            ->values();

        if ($shopIds->isNotEmpty()) {
            $shops = Shop::query()
                ->whereIn('id', $shopIds)
                ->get()
                ->keyBy('id');

            foreach ($shopIds as $shopId) {
                $shop = $shops->get($shopId);
                if (! $shop) {
                    continue;
                }

                $toEmail = $shop->owner_email ?: $shop->email;
                if (! $toEmail) {
                    continue;
                }

                $itemsForShop = collect($cartItems)
                    ->where('shop_id', $shopId)
                    ->map(function (array $item) {
                        unset($item['shop_id']);

                        return $item;
                    })
                    ->values()
                    ->all();

                try {
                    Mail::to($toEmail)->send(new NewOrderForShop($order, $shop, $itemsForShop));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        $request->session()->forget('cart');

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('pages.checkout.success');
    }
}
