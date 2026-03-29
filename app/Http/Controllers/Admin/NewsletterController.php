<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->latest()->paginate(50);
        
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::active()->count(),
            'inactive' => NewsletterSubscriber::inactive()->count(),
            'today' => NewsletterSubscriber::whereDate('created_at', today())->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function create()
    {
        return view('admin.newsletter.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:newsletter_subscribers,email'],
            'name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $data['source'] = 'admin';
        $data['is_active'] = $request->has('is_active');

        NewsletterSubscriber::create($data);

        return redirect()->route('admin.newsletter.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة المشترك بنجاح' : 'Subscriber added successfully');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم حذف المشترك بنجاح' : 'Subscriber deleted successfully');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::active()->get();

        $csv = "Email,Name,Subscribed At\n";
        foreach ($subscribers as $sub) {
            $csv .= "\"{$sub->email}\",\"{$sub->name}\",\"{$sub->subscribed_at}\"\n";
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="newsletter-subscribers-' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function toggleStatus(NewsletterSubscriber $subscriber)
    {
        if ($subscriber->is_active) {
            $subscriber->unsubscribe();
        } else {
            $subscriber->resubscribe();
        }

        return redirect()->route('admin.newsletter.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث الحالة بنجاح' : 'Status updated successfully');
    }
}
