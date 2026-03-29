<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messagesQuery = ContactMessage::query()->latest();

        if ($request->filled('status')) {
            $messagesQuery->where('status', (string) $request->input('status'));
        }

        $messages = $messagesQuery->paginate(20)->withQueryString();

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();

        return view('admin.messages.show', compact('contactMessage'));
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied,archived'],
        ]);

        $contactMessage->update($data);

        if ($data['status'] === 'read') {
            $contactMessage->markAsRead();
        }

        if ($data['status'] === 'replied') {
            $contactMessage->markAsReplied();
        }

        return back()->with('status', 'Saved.');
    }
}
