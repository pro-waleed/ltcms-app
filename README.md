# LTCMS App

نظام لإدارة الفرص التدريبية والترشيحات وطلبات المشاركة وسجل التدريب.

## الحالة الحالية

المشروع أصبح مهيأ مبدئيًا للنشر بالحاويات مع ملفات أساسية للنشر والإعداد:

- `Dockerfile`
- `render.yaml`
- `.env.example`
- `DEPLOYMENT.md`
- صفحة مراجعة جاهزة داخل `public/system-review.html`

## المكونات الرئيسية

- إدارة الفرص التدريبية
- إدارة الموظفين
- إدارة الترشيحات
- إدارة طلبات المشاركة
- إدارة الشركاء والتمويل
- تقارير ولوحات متابعة

## متطلبات التشغيل المحلي

- PHP 8.2 أو أعلى
- Composer
- Node.js 20 أو أعلى
- قاعدة بيانات PostgreSQL أو MySQL أو SQLite

## تشغيل محلي سريع

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run build
php artisan serve
```

## رابط تقرير المراجعة

بعد تشغيل التطبيق، يمكن استعراض تقرير المراجعة من:

```text
/system-review.html
```

مثال محلي:

```text
http://localhost:8000/system-review.html
```

## النشر

تعليمات النشر موجودة في:

- `DEPLOYMENT.md`

وللنشر على Render يوجد:

- `render.yaml`

## ملاحظات مهمة

- تم تجهيز المسار الصحي ` /up ` لفحص الصحة.
- يفضل استخدام PostgreSQL في بيئة الإنتاج.
- يجب ضبط `APP_KEY` ومتغيرات قاعدة البيانات قبل أول نشر فعلي.
- لا تزال هناك أعمال قادمة لتحسين الترميز العربي والاختبارات والتقارير المتقدمة.
