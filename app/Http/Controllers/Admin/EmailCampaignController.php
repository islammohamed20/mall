<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::query()
            ->with('creator')
            ->latest()
            ->paginate(20);

        return view('admin.email-campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $subscribersCount = NewsletterSubscriber::active()->count();
        
        return view('admin.email-campaigns.create', compact('subscribersCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject_ar' => ['required', 'string', 'max:255'],
            'subject_en' => ['required', 'string', 'max:255'],
            'body_ar' => ['required', 'string'],
            'body_en' => ['required', 'string'],
        ]);

        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';
        $data['recipients_count'] = NewsletterSubscriber::active()->count();

        $campaign = EmailCampaign::create($data);

        return redirect()->route('admin.email-campaigns.show', $campaign)
            ->with('success', app()->getLocale() === 'ar' ? 'تم إنشاء الحملة بنجاح' : 'Campaign created successfully');
    }

    public function show(EmailCampaign $emailCampaign)
    {
        $emailCampaign->load('creator');
        
        return view('admin.email-campaigns.show', compact('emailCampaign'));
    }

    public function edit(EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status !== 'draft') {
            return redirect()->route('admin.email-campaigns.show', $emailCampaign)
                ->with('error', app()->getLocale() === 'ar' ? 'لا يمكن تعديل حملة تم إرسالها' : 'Cannot edit sent campaign');
        }

        return view('admin.email-campaigns.edit', compact('emailCampaign'));
    }

    public function update(Request $request, EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status !== 'draft') {
            return redirect()->route('admin.email-campaigns.show', $emailCampaign)
                ->with('error', app()->getLocale() === 'ar' ? 'لا يمكن تعديل حملة تم إرسالها' : 'Cannot edit sent campaign');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject_ar' => ['required', 'string', 'max:255'],
            'subject_en' => ['required', 'string', 'max:255'],
            'body_ar' => ['required', 'string'],
            'body_en' => ['required', 'string'],
        ]);

        $emailCampaign->update($data);

        return redirect()->route('admin.email-campaigns.show', $emailCampaign)
            ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث الحملة بنجاح' : 'Campaign updated successfully');
    }

    public function send(EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status !== 'draft') {
            return redirect()->route('admin.email-campaigns.show', $emailCampaign)
                ->with('error', app()->getLocale() === 'ar' ? 'تم إرسال هذه الحملة مسبقاً' : 'Campaign already sent');
        }

        $subscribers = NewsletterSubscriber::active()->get();
        
        $emailCampaign->update([
            'status' => 'sending',
            'recipients_count' => $subscribers->count(),
        ]);

        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::raw($emailCampaign->body_ar, function ($message) use ($subscriber, $emailCampaign) {
                    $message->to($subscriber->email)
                            ->subject($emailCampaign->subject_ar);
                });
                $sent++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        $emailCampaign->update([
            'status' => 'sent',
            'sent_count' => $sent,
            'failed_count' => $failed,
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.email-campaigns.show', $emailCampaign)
            ->with('success', app()->getLocale() === 'ar' ? "تم إرسال الحملة ل {$sent} مشترك" : "Campaign sent to {$sent} subscribers");
    }

    public function destroy(EmailCampaign $emailCampaign)
    {
        $emailCampaign->delete();

        return redirect()->route('admin.email-campaigns.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم حذف الحملة بنجاح' : 'Campaign deleted successfully');
    }
}
