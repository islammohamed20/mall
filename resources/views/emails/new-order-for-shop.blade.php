@php
    $isAr = app()->getLocale() === 'ar';
@endphp

<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #111;">
    <h2 style="margin: 0 0 12px;">
        {{ $isAr ? 'طلب جديد' : 'New Order' }} — {{ $shop->name }} (#{{ $order->id }})
    </h2>

    <p style="margin: 0 0 12px;">
        {{ $isAr ? 'تفاصيل العميل:' : 'Customer details:' }}
        <br>
        <strong>{{ $isAr ? 'الاسم' : 'Name' }}:</strong> {{ $order->name }}
        <br>
        <strong>{{ $isAr ? 'الهاتف' : 'Phone' }}:</strong> {{ $order->phone }}
        <br>
        <strong>{{ $isAr ? 'الإيميل' : 'Email' }}:</strong> {{ $order->email }}
    </p>

    @if ($order->shipping_address || $order->shipping_city)
        <p style="margin: 0 0 12px;">
            <strong>{{ $isAr ? 'عنوان الشحن' : 'Shipping address' }}:</strong>
            {{ trim(($order->shipping_city ? $order->shipping_city . ' - ' : '') . ($order->shipping_address ?? '')) }}
        </p>
    @endif

    @if ($order->notes)
        <p style="margin: 0 0 12px;">
            <strong>{{ $isAr ? 'ملاحظات' : 'Notes' }}:</strong> {{ $order->notes }}
        </p>
    @endif

    <h3 style="margin: 18px 0 8px;">{{ $isAr ? 'المنتجات' : 'Items' }}</h3>
    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; border-color: #ddd;">
        <thead>
            <tr style="background: #f6f6f6;">
                <th align="left">{{ $isAr ? 'المنتج' : 'Product' }}</th>
                <th align="right">{{ $isAr ? 'الكمية' : 'Qty' }}</th>
                <th align="right">{{ $isAr ? 'السعر' : 'Price' }}</th>
                <th align="right">{{ $isAr ? 'الإجمالي' : 'Subtotal' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] ?? '' }}</td>
                    <td align="right">{{ $item['quantity'] ?? '' }}</td>
                    <td align="right">{{ isset($item['price'], $item['currency']) ? number_format((float) $item['price'], 2) . ' ' . $item['currency'] : '' }}</td>
                    <td align="right">{{ isset($item['subtotal'], $item['currency']) ? number_format((float) $item['subtotal'], 2) . ' ' . $item['currency'] : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin: 12px 0 0; color: #555; font-size: 13px;">
        {{ $isAr ? 'تم إرسال هذه الرسالة تلقائياً من نظام West Albalad Mall.' : 'This message was sent automatically by West Albalad Mall.' }}
    </p>
</div>
