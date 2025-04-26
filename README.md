# Telegram PHP Bot

**بوت تيليجرام مكتوب بـPHP يعمل على Render عبر Webhook**

---

## محتوى المشروع
- **Dockerfile**: بناء صورة PHP/Apache
- **.dockerignore**: لاستثناء الملفات غير المرغوبة
- **index.php**: استقبال Webhook وتمريره إلى البوت
- **appp.php**: سكربت البوت الرئيسي
- **storage/**: مجلد لتخزين الملفات (مؤقت أو Persistent Disk)
- **.gitignore**: تجاهل الملفات غير المهمة

---

## المتطلبات
- حساب على Render
- Docker (للتشغيل محليّيًا أو في CI)
- توكن البوت من BotFather
- ADMIN_ID (معرّف دردشة المدير)

---

## طريقة الإعداد والنشر
1. **تهيئة المتغيرات على Render**  
   - TELEGRAM_TOKEN  
   - ADMIN_ID  
   (Render يوفر RENDER_EXTERNAL_URL تلقائيًا)
2. **بناء ونشر**  
   - اختر في Render نوع الخدمة **Web Service**  
   - اعتمد على Dockerfile  
   - اربط المستودع واضبط المتغيرات  
   - اضغط **Create Web Service** لبدء البناء والنشر  
3. **تسجيل Webhook**  
   افتح في المتصفح: