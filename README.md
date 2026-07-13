# المتجر الإلكتروني | Online Store

متجر إلكتروني مخصص مبني بـ **Laravel 12 + MySQL**، اللغة الأساسية **العربية (RTL)** والإنجليزية لغة ثانوية.

A custom e-commerce store built with **Laravel 12 + MySQL**. Arabic (RTL) is the primary language, English is secondary.

---

## المراحل | Roadmap

| المرحلة | الوصف | الحالة |
|---------|-------|--------|
| 0 | هيكل Laravel 12 + Tailwind CSS v4 | ✅ |
| 1 | نظام المستخدمين والصلاحيات | ✅ |
| 2 | الوسائط (Media) + التصنيفات والمنتجات | ✅ |
| 3 | سلة المشتريات (Cart) | ✅ |
| 4 | الطلبات (Orders) | ✅ |
| 5 | الدفع (COD + انستا باي + محافظ) | ✅ |
| 6 | لوحة الإدارة | ✅ |
| 7 | الإعدادات + سجل النشاطات | ✅ |
| 8 | SEO + الأداء | ✅ |
| 9 | البحث + التحسينات | ✅ |
| 10 | النشر على Hostinger | ✅ |

---

## التشغيل محلياً | Local Setup

### المتطلبات | Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ & npm
- SQLite (للاختبار المحلي) أو MySQL

### خطوات التثبيت | Installation

```bash
# 1. استنساخ المشروع | Clone the repository
git clone <repository-url>
cd online-store

# 2. تثبيت الاعتماديات | Install dependencies
composer install
npm install

# 3. إعداد ملف البيئة | Environment setup
cp .env.example .env
php artisan key:generate

# 4. إعداد قاعدة البيانات | Database setup
# للـ SQLite:
touch database/database.sqlite
php artisan migrate --seed

# أو لـ MySQL: عدّل .env أولاً ببيانات قاعدة البيانات ثم:
# php artisan migrate --seed

# 5. بناء الأصول | Build frontend assets
npm run build

# 6. تشغيل السيرفر | Run the dev server
php artisan serve
```

الموقع المتاح:
- الرئيسية: `http://localhost:8000`
- لوحة الإدارة: `http://localhost:8000/admin`
- بيانات الدخول: `admin@store.com` / `password`

---

## الاختبارات | Tests

```bash
# تشغيل جميع الاختبارات
composer test

# أو مباشرة
php artisan test
```

**الحالة النهائية:** 180 اختبار — 293 تأكيد — 0 أخطاء

---

## النشر على Hostinger | Deploy to Hostinger

### المتطلبات | Requirements

- حساب Hostinger يدعم **PHP 8.2+** و**MySQL**
- Domain أو Subdomain مُعد في hPanel
- Git أو FTP access

### الطريقة الأولى: Git (موصى بها) | Git Deploy (Recommended)

إذا كان حسابك يدعم Git deployment عبر hPanel:

```bash
# 1. أنشئ repository على GitHub/GitLab
# 2. ارفع الكود
# 3. في hPanel → Website → Git → اربط الـ repository
```

### الطريقة الثانية: رفع يدوي عبر File Manager | Manual Upload

#### الخطوة 1: تجهيز الملفات | Prepare Files

```bash
# على جهازك المحلي:
# 1. بناء الأصول للإنتاج
npm ci && npm run build

# 2. تثبيت الاعتماديات بدون dev
composer install --no-dev --optimize-autoloader
```

#### الخطوة 2: رفع الملفات | Upload Files

ارفع **المجلدات والملفات التالية** إلى `public_html`:

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── lang/
├── public/          ← محتوياتها في根 مجلد public_html
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── .user.ini
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── vite.config.js
└── deploy.sh
```

**مهم:** ارفع محتويات مجلد `public/` مباشرة في根 مجلد `public_html`:

```
public_html/              ← (root)
├── index.php             ← من public/index.php
├── .htaccess             ← من public/.htaccess
├── build/                ← من public/build/
├── robots.txt            ← من public/robots.txt
├── app/
├── vendor/
├── artisan
├── .env
└── ...
```

#### الخطوة 3: إعداد قاعدة البيانات | Database Setup

1. في **hPanel → Databases → MySQL Databases**
2. أنشئ قاعدة بيانات جديدة (مثلاً: `u123456789_store`)
3. أنشئ مستخدم قاعدة بيانات (مثلاً: `u123456789_admin`)
4. احفظ **اسم القاعدة** و**اسم المستخدم** و**كلمة المرور**

#### الخطوة 4: إعداد ملف البيئة | Environment Configuration

أنشئ ملف `.env` في根 مجلد `public_html`:

```env
APP_NAME="Online Store"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ar_SA

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456789_store
DB_USERNAME=u123456789_admin
DB_PASSWORD=your_password_here

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### الخطوة 5: توليد مفتاح التطبيق | Generate App Key

```bash
# عبر SSH في Hostinger (متوفر في hPanel → Advanced → SSH Access):
cd ~/public_html
php artisan key:generate
```

#### الخطوة 6: تشغيل الترحيلات | Run Migrations

```bash
# عبر SSH:
cd ~/public_html
php artisan migrate --force
```

#### الخطوة 7: بناء الأصول | Build Assets

```bash
# عبر SSH:
cd ~/public_html
npm ci && npm run build
```

#### الخطوة 8: التشغيل التلقائي | Auto Deploy

```bash
# عبر SSH:
cd ~/public_html
chmod +x deploy.sh
bash deploy.sh
```

### معلومات تسجيل الدخول الافتراضية | Default Login

| الحقل | القيمة |
|-------|--------|
| البريد الإلكتروني | `admin@store.com` |
| كلمة المرور | `password` |

**غيّر كلمة المرور فوراً بعد أول تسجيل دخول!**

---

## إعداد .htaccess | .htaccess Configuration

ملف `public/.htaccess` يحتوي على:

- **توجيه Laravel** — جميع الطلبات تذهب إلى `index.php`
- **أمان** — حظر الملفات المخفية و浏sing المجلدات
- **ضغط Gzip** — تقليل حجم الاستجابة
- **تخزين المؤقت** — ملفات CSS/JS/صور لمدة سنة (Vite يضيف version hash)
- **رؤوس الأمان** — XSS protection, clickjacking prevention

---

## إعداد PHP | PHP Configuration

ملف `.user.ini` في根 مجلد المشروع:

```ini
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300
memory_limit = 256M
opcache.enable=1
opcache.memory_consumption=128
```

> **ملاحظة:** بعض مضيفي Hostinger قد لا يدعمون `opcache.enable` في `.user.ini`. إذا ظهر خطأ، احذف سطر `opcache.enable`.

---

## حل المشكلات الشائعة | Troubleshooting

### 1. خطأ 500 — Internal Server Error

```bash
# تحقق من السجلات:
tail -50 storage/logs/laravel.log

# تأكد من أن .env موجود و APP_KEY مُولّد
php artisan key:generate
```

### 2. ملفات CSS/JS غير موجودة (تصميم مكسور)

```bash
# أعد بناء الأصول:
npm ci && npm run build
```

### 3. خطأ في قاعدة البيانات

```bash
# تحقق من إعدادات .env:
grep DB_ .env

# أعد تشغيل الترحيلات:
php artisan migrate --force
```

### 4. رفع الملفات لا يعمل

- تأكد من أن `storage` و `bootstrap/cache` writable:
  ```bash
  chmod -R 775 storage
  chmod -R 775 bootstrap/cache
  ```

### 5. الـ Symlink لا يعمل

```bash
php artisan storage:link
```

### 6. الصفحة الرئيسية تظهر كنص بدلاً من تصميم

- تأكد أن `.htaccess` موجود في根 مجلد `public_html`
- تأكد أن `mod_rewrite` مفعّل في Apache

### 7.ails in cron jobs / queues

Hostinger Shared Hosting لا يدعم `php artisan queue:work` مباشرة. استخدم `QUEUE_CONNECTION=database` وتشغيل الـ queue عبر cron:

```bash
# في hPanel → Cron Jobs:
* * * * * cd ~/public_html && php artisan queue:work --sleep=3 --tries=3
```

### 8.ails in email

- تأكد من إعداد SMTP في `.env`
- استخدم `MAIL_MAILER=log` أولاً للتأكد من أن الكود يعمل
- ثم انتقل إلى `MAIL_MAILER=smtp` مع بيانات المزود

---

## هيكل المشروع | Project Structure

```
app/
├── Concerns/           ← Traits (Sluggable, HasMedia, LogsActivity)
├── Console/            ← Artisan commands
├── Enums/              ← UserRole, OrderStatus, PaymentStatus
├── Exceptions/         ← Custom exceptions
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      ← DashboardController, CategoryController, ProductController...
│   │   ├── Auth/       ← LoginController, RegisterController
│   │   └── Store/      ← ProductController, CartController, CheckoutController...
│   ├── Middleware/      ← EnsureUserIsAdmin, SetLocale
│   └── Requests/       ← Form validation (Admin/)
├── Models/             ← Eloquent models (User, Product, Order, etc.)
├── Policies/           ← Authorization policies
├── Providers/          ← Service providers
├── Repositories/       ← Repository pattern (Contracts/ + Eloquent/)
├── Search/             ← Search engine abstraction
├── Services/           ← Business logic (CartService, OrderService, etc.)
└── Support/            ← Helper classes (Seo.php)

database/
├── factories/          ← Model factories for testing
├── migrations/         ← Database migrations (20 tables)
└── seeders/            ← Seeders (AdminUserSeeder, etc.)

resources/
├── css/                ← Tailwind CSS v4 (@import "tailwindcss")
├── js/                 ← JavaScript (Alpine.js, etc.)
├── lang/               ← Arabic + English translations
└── views/
    ├── admin/          ← Admin panel Blade views
    ├── auth/           ← Login/Register views
    ├── layouts/        ← app.blade.php, admin.blade.php
    └── store/          ← Store Blade views

tests/
├── Feature/
│   ├── Admin/          ← Admin feature tests (140+ tests)
│   └── Store/          ← Store feature tests
└── Unit/
    ├── Models/         ← Model unit tests
    ├── Search/         ← Search engine tests
    └── Services/       ← Service unit tests
```

---

## ملاحظات تقنية | Technical Notes

- **قاعدة البيانات:** SQLite محلياً، MySQL على Hostinger
- **البحث:** `DatabaseSearchEngine` (LIKE queries) — جاهز لـ Meilisearch
- **السلة:** تتبع عبر المستخدم (Session-based محلياً)
- **الدفع:** COD + إيصال تحويل + انستا باي (قابل للتوسيع)
- **الترجمة:** جميع النصوص عبر `__()` — لا نصوص ثابتة
- **الأمان:** Policies + Middleware — لا ملفات معرّفة في المجلدات

---

## الترخيص | License

Proprietary — جميع الحقوق محفوظة.
