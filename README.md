# Mall (Laravel)

موقع مول + لوحة تحكم (Admin Panel) لإدارة المحتوى والطلبات والتقارير.

This repository contains a Mall website + Admin Panel built with Laravel.

## المحتويات

- [المميزات (الواجهة العامة)](#المميزات-الواجهة-العامة)
- [المميزات (لوحة التحكم)](#المميزات-لوحة-التحكم)
- [الاختصارات داخل لوحة التحكم](#الاختصارات-داخل-لوحة-التحكم)
- [التقارير والتحليلات](#التقارير-والتحليلات)
- [إعدادات البريد SMTP (من لوحة التحكم)](#إعدادات-البريد-smtp-من-لوحة-التحكم)
- [تتبّع الزيارات + موقع الجهاز](#تتبّع-الزيارات--موقع-الجهاز)
- [التشغيل محليًا](#التشغيل-محليًا)
- [النشر على السيرفر](#النشر-على-السيرفر)
- [Transfer / Deployment ZIP](#transfer--deployment-zip)

## Tech Stack

- Laravel 12 + PHP 8.x
- Blade + TailwindCSS + Alpine.js
- SQLite (افتراضيًا في هذا المشروع)
- Vite (Front-end build)

## المميزات (الواجهة العامة)

- تعدد اللغات: عربي / English (مع اتجاه RTL/LTR).
- ثيم Light/Dark.
- الصفحة الرئيسية + أقسام (محلات/عروض/فعاليات/وحدات).
- صفحة المحلات + صفحة المحل (Shop) + المنتجات.
- المفضلة (Favorites) للزائر (Session-based) مع عدّاد.
- سلة (Cart) للزائر (Session-based) + Checkout.
- حسابي (My Account) للمستخدم المسجل: بيانات المستخدم + الطلبات.
- صفحة تواصل معنا (Contact) + حفظ الرسائل.
- وحدات للبيع/الإيجار (Units for Sale/Rent).

### الدفع

- دعم COD (الدفع عند الاستلام).
- طريقة الدفع بالبطاقة (Card/Visa) **لا تظهر للعميل** إلا عند تفعيلها من الإعدادات البيئية (Feature flag) وربط بوابة دفع فعلية.

## المميزات (لوحة التحكم)

- إدارة المحتوى:
	- تصنيفات المحلات
	- المحلات
	- خصائص المنتجات
	- العروض
	- الفعاليات
	- السلايدر
	- الأدوار + ربطها بالمحلات
	- المرافق والخدمات
	- الصفحات
	- الوحدات المعروضة
	- طرق الدفع
- إدارة الطلبات (Orders): عرض/تفاصيل/تغيير الحالة.
- الرسائل (Messages): استعراض رسائل التواصل وتغيير الحالة.
- إعدادات الموقع العامة: الاسم، الشعار، اللوجو، الـfavicon، التواصل، الخريطة، السوشيال، أرقام سريعة…
- إعدادات البريد الإلكتروني (SMTP) من لوحة التحكم + إمكانية إرسال بريد تجريبي.

## الاختصارات داخل لوحة التحكم

مطبقة عالميًا على كل صفحات لوحة التحكم (لأنها داخل layout الإدارة).

- `Ctrl/⌘ + S` حفظ / Submit الفورم الحالي
- `Ctrl/⌘ + Enter` Submit سريع (مفيد للفلاتر)
- `Ctrl/⌘ + Shift + D` مسح الحقول (قد يتعارض مع اختصارات المتصفح في بعض الأجهزة)
- `Ctrl/⌘ + Shift + X` مسح الحقول (بديل موصى به)
- `Ctrl/⌘ + Shift + T` تبديل الثيم (Dark/Light)
- `Esc` إغلاق القائمة الجانبية على الموبايل

## التقارير والتحليلات

داخل لوحة التحكم يوجد قسم تقارير يشمل (حسب الموجود حاليًا في النظام):

- Sales Report
- Orders Report
- Shops Report
- Products Report
- Customers Report
- Offers & Events Report
- Messages Report
- Visits Report (تقرير الزيارات)

## إعدادات البريد SMTP (من لوحة التحكم)

من داخل لوحة التحكم يمكن ضبط:

- `mail_mailer` (smtp / sendmail / log)
- host/port/username/password/encryption
- `From Address` و`From Name`
- إرسال بريد تجريبي للتأكد من الإعدادات

ملاحظة: النظام يدعم تطبيق إعدادات البريد ديناميكيًا من قاعدة البيانات (Settings) عند وجود `mail_mailer` محفوظ في جدول `settings`.

## تتبّع الزيارات + موقع الجهاز

تم إضافة نظام تتبّع زيارات للواجهة العامة:

- يسجل الزيارة (المسار، نوع الجهاز، المتصفح، المنصة، referrer…)
- يحدد الزائر عبر Cookie باسم `mall_vid`
- دعم موقع الجهاز (Lat/Lng) **اختياري** من خلال إذن المتصفح

ملاحظات مهمة:

- تحديد الموقع يتطلب عادةً HTTPS + موافقة المستخدم. على HTTP غالبًا لن يظهر طلب الإذن.
- لا يتم إجبار المستخدم على مشاركة الموقع.

## التشغيل محليًا

### المتطلبات

- PHP 8.x
- Composer
- Node.js + npm

### خطوات التشغيل

1) تثبيت الاعتمادات:

```bash
composer install
npm install
```

2) إعداد البيئة:

```bash
cp .env.example .env
php artisan key:generate
```

3) قاعدة البيانات (SQLite افتراضيًا):

```bash
php artisan migrate
```

4) ملفات التخزين (Uploads):

```bash
php artisan storage:link
```

5) بناء الواجهة:

```bash
npm run build
```

6) تشغيل السيرفر:

```bash
php artisan serve
```

## النشر على السيرفر

- تأكد من ضبط صلاحيات `storage/` و`bootstrap/cache/`.
- شغّل:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

ملاحظة: تحديد الموقع (Geolocation) يعمل بشكل أفضل على HTTPS.

## Transfer / Deployment ZIP

لإنشاء ملف ZIP للنقل **يشمل dotfiles** (مثل `public/.htaccess`) ويستثني مجلدات التطوير/الكاش:

```bash
bash scripts/make-transfer-zip.sh
```

## Notes

- لا تقم برفع ملف `.env` إلى GitHub.
- عند تفعيل البريد الحقيقي، غيّر `MAIL_MAILER` من `log` إلى `smtp` أو استخدم إعدادات البريد من لوحة التحكم.

