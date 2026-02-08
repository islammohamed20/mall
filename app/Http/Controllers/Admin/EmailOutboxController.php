<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailOutbox;
use Illuminate\Http\Request;

class EmailOutboxController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailOutbox::query()->latest('id');

        if ($request->filled('q')) {
            $q = trim((string) $request->string('q'));
            $query->where(function ($builder) use ($q) {
                $builder->where('subject', 'like', "%{$q}%")
                    ->orWhere('mailable', 'like', "%{$q}%")
                    ->orWhere('message_id', 'like', "%{$q}%")
                    ->orWhere('to', 'like', "%{$q}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->date('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->date('to_date'));
        }

        $emails = $query->paginate(25)->withQueryString();

        return view('admin.emails.outbox.index', compact('emails'));
    }

    public function show(EmailOutbox $emailOutbox)
    {
        return view('admin.emails.outbox.show', ['email' => $emailOutbox]);
    }
}
