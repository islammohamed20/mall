<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckoutOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $paymentType = $request->get('payment_type');

        $query = CheckoutOrder::with(['user', 'paymentMethod']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($paymentType) {
            $query->where('payment_type', $paymentType);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders', 'status', 'paymentType'));
    }

    public function show(CheckoutOrder $order)
    {
        $order->load(['user', 'paymentMethod']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, CheckoutOrder $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', app()->getLocale() === 'ar' 
            ? 'تم تحديث حالة الطلب بنجاح' 
            : 'Order status updated successfully');
    }
}
