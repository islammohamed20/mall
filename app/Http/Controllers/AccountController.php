<?php

namespace App\Http\Controllers;

use App\Models\CheckoutOrder;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $orders = CheckoutOrder::query()
            ->with(['paymentMethod'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('pages.account.show', compact('user', 'orders'));
    }
}
