<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function store(ContactMessageRequest $request)
    {
        ContactMessage::create($request->validated());

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم إرسال رسالتك بنجاح.' : 'Your message has been sent.');
    }
}
