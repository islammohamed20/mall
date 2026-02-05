<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_id' => ['nullable', 'integer', 'exists:shops,id'],
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:offers,slug'],
            'short_ar' => ['nullable', 'string', 'max:255'],
            'short_en' => ['nullable', 'string', 'max:255'],
            'content_ar' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'banner_image' => ['nullable', 'image', 'max:4096'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'discount_text_ar' => ['nullable', 'string', 'max:255'],
            'discount_text_en' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
