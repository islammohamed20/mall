@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل الوحدة' : 'Edit Unit' }}</h1>
        <a href="{{ route('admin.units.index') }}" class="btn-white">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
    </div>

    <form action="{{ route('admin.units.update', $unit) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.units._form')
        <div class="mt-6">
            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'تحديث' : 'Update' }}</button>
        </div>
    </form>
@endsection
