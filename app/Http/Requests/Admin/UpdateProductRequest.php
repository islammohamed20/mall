<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $shop = $this->route('shop');
        $product = $this->route('product');

        $rules = [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')
                    ->where('shop_id', $shop?->id)
                    ->ignore($product?->id),
            ],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'sku' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'image' => ['nullable', 'image', 'max:4096'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        $category = $shop?->category;
        if ($category) {
            $attributes = $category->productAttributes()->where('is_active', true)->get();
            foreach ($attributes as $attribute) {
                $key = 'attributes.'.$attribute->id;
                $ruleString = $attribute->validation_rules ?: 'nullable';
                $rules[$key] = array_filter(array_map('trim', explode('|', $ruleString)));
            }
        }

        return $rules;
    }
}
