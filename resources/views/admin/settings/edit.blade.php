@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إعدادات الموقع' : 'Site Settings' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'تحديث بيانات المول وروابط التواصل والمحتوى الظاهر في الموقع.' : 'Update mall info, social links, and content shown publicly.' }}</p>
            </div>
        </div>

        <form class="admin-card p-6 space-y-8" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الهوية' : 'Branding' }}</div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'لوجو الموقع' : 'Site Logo' }}</label>
                        <input class="form-input" type="file" name="mall_logo" accept="image/*" />
                        @error('mall_logo') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        @if (!empty($values['mall_logo']['ar']))
                            <div class="mt-3 rounded-xl overflow-hidden bg-gray-100 dark:bg-secondary-900 h-20 w-20">
                                <img class="w-full h-full object-cover" src="{{ asset('storage/'.$values['mall_logo']['ar']) }}" alt="" loading="lazy" />
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Fav Icon' : 'Favicon' }}</label>
                        <input class="form-input" type="file" name="mall_favicon" accept="image/png,image/x-icon" />
                        @error('mall_favicon') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        @if (!empty($values['mall_favicon']['ar']))
                            <div class="mt-3 rounded-xl overflow-hidden bg-gray-100 dark:bg-secondary-900 h-10 w-10">
                                <img class="w-full h-full object-cover" src="{{ asset('storage/'.$values['mall_favicon']['ar']) }}" alt="" loading="lazy" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'عام' : 'General' }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم المول (عربي)' : 'Mall Name (AR)' }}</label>
                        <input class="form-input" name="mall_name[ar]" value="{{ $values['mall_name']['ar'] }}" />
                        @error('mall_name.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم المول (English)' : 'Mall Name (EN)' }}</label>
                        <input class="form-input" name="mall_name[en]" value="{{ $values['mall_name']['en'] }}" />
                        @error('mall_name.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الشعار (عربي)' : 'Slogan (AR)' }}</label>
                        <input class="form-input" name="mall_slogan[ar]" value="{{ $values['mall_slogan']['ar'] }}" />
                        @error('mall_slogan.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الشعار (English)' : 'Slogan (EN)' }}</label>
                        <input class="form-input" name="mall_slogan[en]" value="{{ $values['mall_slogan']['en'] }}" />
                        @error('mall_slogan.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'التواصل' : 'Contact' }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الهاتف (عربي)' : 'Phone (AR)' }}</label>
                        <input class="form-input" name="mall_contact_phone[ar]" value="{{ $values['mall_contact_phone']['ar'] }}" />
                        @error('mall_contact_phone.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الهاتف (English)' : 'Phone (EN)' }}</label>
                        <input class="form-input" name="mall_contact_phone[en]" value="{{ $values['mall_contact_phone']['en'] }}" />
                        @error('mall_contact_phone.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'واتساب (عربي)' : 'WhatsApp (AR)' }}</label>
                        <input class="form-input" name="mall_contact_whatsapp[ar]" value="{{ $values['mall_contact_whatsapp']['ar'] }}" />
                        @error('mall_contact_whatsapp.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'واتساب (English)' : 'WhatsApp (EN)' }}</label>
                        <input class="form-input" name="mall_contact_whatsapp[en]" value="{{ $values['mall_contact_whatsapp']['en'] }}" />
                        @error('mall_contact_whatsapp.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني (عربي)' : 'Email (AR)' }}</label>
                        <input class="form-input" name="mall_contact_email[ar]" value="{{ $values['mall_contact_email']['ar'] }}" />
                        @error('mall_contact_email.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني (English)' : 'Email (EN)' }}</label>
                        <input class="form-input" name="mall_contact_email[en]" value="{{ $values['mall_contact_email']['en'] }}" />
                        @error('mall_contact_email.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان (عربي)' : 'Address (AR)' }}</label>
                        <textarea class="form-input" rows="3" name="mall_contact_address[ar]">{{ $values['mall_contact_address']['ar'] }}</textarea>
                        @error('mall_contact_address.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان (English)' : 'Address (EN)' }}</label>
                        <textarea class="form-input" rows="3" name="mall_contact_address[en]">{{ $values['mall_contact_address']['en'] }}</textarea>
                        @error('mall_contact_address.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'ساعات العمل' : 'Working Hours' }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ساعات العمل (عربي)' : 'Working hours (AR)' }}</label>
                        <textarea class="form-input" rows="3" name="mall_working_hours[ar]">{{ $values['mall_working_hours']['ar'] }}</textarea>
                        @error('mall_working_hours.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ساعات العمل (English)' : 'Working hours (EN)' }}</label>
                        <textarea class="form-input" rows="3" name="mall_working_hours[en]">{{ $values['mall_working_hours']['en'] }}</textarea>
                        @error('mall_working_hours.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الخريطة' : 'Map' }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رابط Embed (عربي)' : 'Embed URL (AR)' }}</label>
                        <textarea class="form-input" rows="3" name="mall_map_embed_url[ar]">{{ $values['mall_map_embed_url']['ar'] }}</textarea>
                        @error('mall_map_embed_url.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رابط Embed (English)' : 'Embed URL (EN)' }}</label>
                        <textarea class="form-input" rows="3" name="mall_map_embed_url[en]">{{ $values['mall_map_embed_url']['en'] }}</textarea>
                        @error('mall_map_embed_url.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'روابط السوشيال' : 'Social Links' }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Facebook (عربي)' : 'Facebook (AR)' }}</label>
                        <input class="form-input" name="mall_social_facebook[ar]" value="{{ $values['mall_social_facebook']['ar'] }}" />
                        @error('mall_social_facebook.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Facebook (English)' : 'Facebook (EN)' }}</label>
                        <input class="form-input" name="mall_social_facebook[en]" value="{{ $values['mall_social_facebook']['en'] }}" />
                        @error('mall_social_facebook.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Instagram (عربي)' : 'Instagram (AR)' }}</label>
                        <input class="form-input" name="mall_social_instagram[ar]" value="{{ $values['mall_social_instagram']['ar'] }}" />
                        @error('mall_social_instagram.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Instagram (English)' : 'Instagram (EN)' }}</label>
                        <input class="form-input" name="mall_social_instagram[en]" value="{{ $values['mall_social_instagram']['en'] }}" />
                        @error('mall_social_instagram.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Twitter (عربي)' : 'Twitter (AR)' }}</label>
                        <input class="form-input" name="mall_social_twitter[ar]" value="{{ $values['mall_social_twitter']['ar'] }}" />
                        @error('mall_social_twitter.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Twitter (English)' : 'Twitter (EN)' }}</label>
                        <input class="form-input" name="mall_social_twitter[en]" value="{{ $values['mall_social_twitter']['en'] }}" />
                        @error('mall_social_twitter.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'TikTok (عربي)' : 'TikTok (AR)' }}</label>
                        <input class="form-input" name="mall_social_tiktok[ar]" value="{{ $values['mall_social_tiktok']['ar'] }}" />
                        @error('mall_social_tiktok.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'TikTok (English)' : 'TikTok (EN)' }}</label>
                        <input class="form-input" name="mall_social_tiktok[en]" value="{{ $values['mall_social_tiktok']['en'] }}" />
                        @error('mall_social_tiktok.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Snapchat (عربي)' : 'Snapchat (AR)' }}</label>
                        <input class="form-input" name="mall_social_snapchat[ar]" value="{{ $values['mall_social_snapchat']['ar'] }}" />
                        @error('mall_social_snapchat.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Snapchat (English)' : 'Snapchat (EN)' }}</label>
                        <input class="form-input" name="mall_social_snapchat[en]" value="{{ $values['mall_social_snapchat']['en'] }}" />
                        @error('mall_social_snapchat.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'أرقام سريعة' : 'Quick Stats' }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'محلات (عربي)' : 'Shops (AR)' }}</label>
                        <input class="form-input" name="mall_stats_shops[ar]" value="{{ $values['mall_stats_shops']['ar'] }}" />
                        @error('mall_stats_shops.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'محلات (English)' : 'Shops (EN)' }}</label>
                        <input class="form-input" name="mall_stats_shops[en]" value="{{ $values['mall_stats_shops']['en'] }}" />
                        @error('mall_stats_shops.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مطاعم (عربي)' : 'Restaurants (AR)' }}</label>
                        <input class="form-input" name="mall_stats_restaurants[ar]" value="{{ $values['mall_stats_restaurants']['ar'] }}" />
                        @error('mall_stats_restaurants.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مطاعم (English)' : 'Restaurants (EN)' }}</label>
                        <input class="form-input" name="mall_stats_restaurants[en]" value="{{ $values['mall_stats_restaurants']['en'] }}" />
                        @error('mall_stats_restaurants.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مواقف (عربي)' : 'Parking (AR)' }}</label>
                        <input class="form-input" name="mall_stats_parking_spots[ar]" value="{{ $values['mall_stats_parking_spots']['ar'] }}" />
                        @error('mall_stats_parking_spots.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مواقف (English)' : 'Parking (EN)' }}</label>
                        <input class="form-input" name="mall_stats_parking_spots[en]" value="{{ $values['mall_stats_parking_spots']['en'] }}" />
                        @error('mall_stats_parking_spots.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'زوار شهرياً (عربي)' : 'Monthly Visitors (AR)' }}</label>
                        <input class="form-input" name="mall_stats_monthly_visitors[ar]" value="{{ $values['mall_stats_monthly_visitors']['ar'] }}" />
                        @error('mall_stats_monthly_visitors.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'زوار شهرياً (English)' : 'Monthly Visitors (EN)' }}</label>
                        <input class="form-input" name="mall_stats_monthly_visitors[en]" value="{{ $values['mall_stats_monthly_visitors']['en'] }}" />
                        @error('mall_stats_monthly_visitors.en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4" x-data="{ monitorEmail: @js($values['admin_monitor_email']['ar'] ?? ''), popupEnabled: @js($values['admin_popup_enabled']['ar'] ?? '1') }">
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'مراقبة الأدمن' : 'Admin Monitoring' }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'إيميل متابعة الأدمن' : 'Admin monitor email' }}</label>
                        <input class="form-input" name="admin_monitor_email[ar]" x-model="monitorEmail" placeholder="admin@example.com" />
                        <input type="hidden" name="admin_monitor_email[en]" x-model="monitorEmail" />
                        @error('admin_monitor_email.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Popups داخل لوحة التحكم' : 'Admin popup notifications' }}</label>
                        <select class="form-input" name="admin_popup_enabled[ar]" x-model="popupEnabled">
                            <option value="1">{{ app()->getLocale() === 'ar' ? 'مفعل' : 'Enabled' }}</option>
                            <option value="0">{{ app()->getLocale() === 'ar' ? 'معطل' : 'Disabled' }}</option>
                        </select>
                        <input type="hidden" name="admin_popup_enabled[en]" x-model="popupEnabled" />
                        @error('admin_popup_enabled.ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection
