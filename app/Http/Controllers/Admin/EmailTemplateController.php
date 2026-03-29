<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::query()->latest()->paginate(20);
        
        return view('admin.email-templates.index', compact('templates'));
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $data = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'subject_ar' => ['required', 'string', 'max:255'],
            'subject_en' => ['required', 'string', 'max:255'],
            'body_ar' => ['required', 'string'],
            'body_en' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $emailTemplate->update($data);

        return redirect()->route('admin.email-templates.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث القالب بنجاح' : 'Template updated successfully');
    }
}
