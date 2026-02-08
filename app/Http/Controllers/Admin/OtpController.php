<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function index(Request $request)
    {
        $query = Otp::query()->latest('id');

        if ($request->filled('email')) {
            $query->where('email', 'like', '%'.trim((string) $request->input('email')).'%');
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        if ($request->filled('used')) {
            $used = (string) $request->input('used') === '1';
            $query->where('used', $used);
        }

        if ($request->filled('expired')) {
            $expired = (string) $request->input('expired') === '1';
            $query->where('expires_at', $expired ? '<=' : '>', now());
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        $otps = $query->paginate(30)->withQueryString();

        return view('admin.otps.index', compact('otps'));
    }
}
