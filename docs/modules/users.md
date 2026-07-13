# وحدة المستخدمين والصلاحيات | Users & Roles Module

## نظرة عامة

توفر هذه الوحدة: التسجيل، تسجيل الدخول/الخروج، إدارة الملف الشخصي، ونظام أدوار بسيط (عميل/أدمن) بدون أي Packages خارجية.

## الأدوار (Roles)

- معرفة في Enum: `App\Enums\UserRole` (`customer`, `admin`).
- مخزنة في عمود `role` بجدول `users` (الافتراضي: `customer`).
- **أمان**: `role` ليس ضمن `$fillable` لمنع تصعيد الصلاحيات عبر Mass Assignment، ويوجد اختبار يؤكد ذلك.
- التحقق عبر `User::isAdmin()`.

## المسارات

| Method | URI | الوصف |
|--------|-----|-------|
| GET/POST | `/register` | إنشاء حساب (للزوار فقط) |
| GET/POST | `/login` | تسجيل الدخول (مع throttle 5/دقيقة) |
| POST | `/logout` | تسجيل الخروج |
| GET/PATCH | `/profile` | عرض/تحديث الملف الشخصي |
| PUT | `/profile/password` | تغيير كلمة المرور |
| GET | `/admin` | لوحة الإدارة (middleware: `auth` + `admin`) |
| GET | `/lang/{locale}` | تبديل اللغة (ar/en) |

## Middleware

- `SetLocale`: يطبق اللغة المخزنة في الجلسة (الافتراضي: عربي). مضاف لمجموعة `web`.
- `EnsureUserIsAdmin` (alias: `admin`): يرفض غير الأدمن بـ 403.

## حساب الأدمن الافتراضي

ينشأ عبر `AdminUserSeeder`:

- البريد: `admin@store.test`
- كلمة المرور: `password`

> ⚠️ **مهم**: غيّر هذه البيانات فوراً في بيئة الإنتاج.

## الاختبارات

- `tests/Feature/Auth/RegistrationTest.php` - التسجيل + منع تصعيد الصلاحيات.
- `tests/Feature/Auth/LoginTest.php` - الدخول/الخروج.
- `tests/Feature/AdminAccessTest.php` - صلاحيات لوحة الإدارة.
- `tests/Feature/ProfileTest.php` - تحديث الملف الشخصي وكلمة المرور.
- `tests/Feature/LocaleTest.php` - تبديل اللغة.

## ملاحظات

- رسائل التحقق (validation) تستخدم الافتراضي الإنجليزي حالياً؛ ستضاف ترجمة `validation.php` العربية الكاملة في مرحلة لاحقة.
