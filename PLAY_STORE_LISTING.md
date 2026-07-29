# Google Play — Store Listing (Metadata policy compliant)

**ناوی فەرمی ئەپ:** **خوێندن** (کوردی) · **Khwenden** (لاتینی)
**دۆخ:** ئەپەکە ڕیجێکت کرابوو بەهۆی *Metadata policy* (وشەی *trusted*).

دەقی خوارەوە کۆپی بکە بۆ **Play Console → Grow → Store presence → Main store listing**.

> **یاسای بنەڕەتی:** باسی ئەوە بکە کە ئەپەکە **چی دەکات**. هەرگیز باسی ئەوە مەکە کە
> **چەندە باشە**. هەموو وشەیەکی هەڵسەنگاندن (باشترین، متمانەپێکراو، پێشەنگ، یەکەمین)
> قەدەغەیە.

---

## ١. ناوی ئەپ — دۆخی ئێستا ✅

ناوەکە لە هەموو شوێنێکی کۆددا **Khwenden**ـە:

| شوێن | نرخ | دۆخ |
|---|---|---|
| `AndroidManifest.xml` → `android:label` | `Khwenden` | ✅ |
| `ios/Runner/Info.plist` → `CFBundleDisplayName` / `CFBundleName` | `Khwenden` | ✅ |
| `lib/app.dart` → `MaterialApp.title` | `Khwenden` | ✅ |
| `lib/core/localization/app_localizations.dart` (٥ زمان) | `Khwenden` | ✅ |
| `lib/features/splash/splash_screen.dart` | `Khwenden` | ✅ |
| `pubspec.yaml` → `description` | `Khwenden - Xwendngakan` | ✅ |
| بانەرەکانی ناو ئەپ (`banners/`) | `Khwenden` | ✅ |
| Feature graphic | `Khwenden` | ✅ (نوێکرایەوە) |
| ماڵپەڕ و لاپەڕەکانی backend | `Khwenden` | ✅ |
| **Play Console → App name** | ← **تۆ دەبێت بیگۆڕیت** | ⬜ |

### ⛔ ئەمانە **هەرگیز** مەگۆڕە

ئەمانە ناسنامەی تەکنیکین، نەک ناوی پیشاندان. بەکارهێنەر و پێداچوونەوەکاری Google
هەرگیز نایانبینن، و گۆڕینیان ئەپەکەت تێکدەدات:

| ناسنامە | نرخ | ئەگەر بگۆڕدرێت |
|---|---|---|
| Android package | `com.koshsh.xwendngakan` | ئەپەکە لە Play دەبێتە ئەپێکی نوێ، هەموو بەکارهێنەرەکان لەدەست دەچن |
| iOS Bundle ID | `com.pti.edubook` | لە App Store دەبێتە ئەپێکی نوێ |
| Firebase / `google-services.json` | `com.koshsh.xwendngakan` | Notification و Auth ڕادەوەستێت |
| Deep link scheme | `edubook://` , `https://edubook.app/` | QR سکانەر کار ناکات |
| Admin session cookie | `edubook_admin_session` | ئەدمینەکان دەردەچن (کێشەی گەورە نییە) |

> وشەی *edubook* لەم شوێنانەدا مانەوەی هیچ کێشەیەکی سیاسەت دروست ناکات —
> Metadata policy تەنها سەیری **title, icon, description, screenshots, developer name**
> دەکات.

---

## ٢. App name (زۆرترین ٣٠ پیت)

| زمان | دەق |
|---|---|
| کوردی (سۆرانی/بادینی) | `خوێندن` |
| عەربی | `خوێندن` یان `Khwenden` |
| ئینگلیزی | `Khwenden` |
| تورکی | `Khwenden` |

❌ هیچ کاتێک: `Khwenden - Best Education App` / `#1` / `Top` / `Free` / `2026`

---

## ٣. Short description (زۆرترین ٨٠ پیت)

**English**
```
Browse universities, institutes, schools and teachers, and contact them.
```

**کوردی**
```
گەڕان بەدوای زانکۆ، پەیمانگا، قوتابخانە و مامۆستا و پەیوەندیکردن پێیان.
```

**عەربی**
```
تصفح الجامعات والمعاهد والمدارس والمدرسين وتواصل معهم.
```

**تورکی**
```
Üniversiteleri, enstitüleri, okulları ve öğretmenleri bulun ve iletişime geçin.
```

---

## ٤. Full description

**English**
```
Khwenden is an education directory app for Kurdistan. Students can browse
universities, institutes, schools and teachers, view their profiles, and
contact them directly.

Features:
• Search educational institutions by city, district, type (public or private) and study level
• Institution profiles with photos, address, departments and contact details
• Find teachers and tutors by subject and city
• Send your CV and apply for jobs published by institutions
• Save favourites and share profiles with others
• Available in Kurdish (Sorani and Badini), Arabic, English and Turkish

Institutions and teachers can create an account, publish their profile and
receive messages from students.

The app is free to use and requires an internet connection.
```

**کوردی**
```
خوێندن ئەپێکی ڕێنمایی خوێندنە بۆ کوردستان. خوێندکاران دەتوانن بگەڕێن بەدوای
زانکۆ، پەیمانگا، قوتابخانە و مامۆستادا، پرۆفایلەکانیان ببینن و ڕاستەوخۆ
پەیوەندییان پێوە بکەن.

تایبەتمەندییەکان:
• گەڕان بەدوای ناوەندەکانی خوێندن بەپێی شار، قەزا، جۆر (حکومی/تایبەت) و ئاستی خوێندن
• پرۆفایلی ناوەندەکان لەگەڵ وێنە، ناونیشان، بەشەکان و زانیاری پەیوەندی
• دۆزینەوەی مامۆستا بەپێی بابەت و شار
• ناردنی CV و داواکاری بۆ کارە بڵاوکراوەکانی ناوەندەکان
• زیادکردن بۆ دڵخوازەکان و هاوبەشکردنی پرۆفایل
• بەردەست بە کوردی (سۆرانی و بادینی)، عەرەبی، ئینگلیزی و تورکی

ناوەندەکان و مامۆستایان دەتوانن هەژمار دروست بکەن، پرۆفایلیان بڵاو بکەنەوە و
نامە لە خوێندکارانەوە وەربگرن.

ئەپەکە بەخۆڕایییە و پێویستی بە ئینتەرنێتە.
```

**عەربی**
```
خوێندن (Khwenden) هو تطبيق دليل تعليمي لكردستان. يمكن للطلاب تصفح الجامعات
والمعاهد والمدارس والمدرسين، والاطلاع على ملفاتهم، والتواصل معهم مباشرة.

الميزات:
• البحث عن المؤسسات التعليمية حسب المدينة والقضاء والنوع (حكومي أو خاص) والمرحلة الدراسية
• ملفات المؤسسات مع الصور والعنوان والأقسام ومعلومات الاتصال
• العثور على المدرسين حسب المادة والمدينة
• إرسال السيرة الذاتية والتقديم على الوظائف المنشورة
• حفظ المفضلة ومشاركة الملفات
• متوفر بالكردية (سوراني وباديني) والعربية والإنجليزية والتركية

يمكن للمؤسسات والمدرسين إنشاء حساب ونشر ملفهم واستلام رسائل من الطلاب.

التطبيق مجاني ويتطلب اتصالاً بالإنترنت.
```

**تورکی**
```
Khwenden, Kürdistan için bir eğitim rehberi uygulamasıdır. Öğrenciler
üniversiteleri, enstitüleri, okulları ve öğretmenleri inceleyebilir,
profillerini görüntüleyebilir ve doğrudan iletişime geçebilir.

Özellikler:
• Eğitim kurumlarını şehre, ilçeye, türe (devlet veya özel) ve eğitim seviyesine göre arayın
• Fotoğraflar, adres, bölümler ve iletişim bilgileriyle kurum profilleri
• Branşa ve şehre göre öğretmen ve özel ders veren bulun
• CV gönderin ve kurumların yayınladığı iş ilanlarına başvurun
• Favorilere ekleyin ve profilleri paylaşın
• Kürtçe (Sorani ve Badini), Arapça, İngilizce ve Türkçe olarak mevcuttur

Kurumlar ve öğretmenler hesap oluşturabilir, profillerini yayınlayabilir ve
öğrencilerden mesaj alabilir.

Uygulama ücretsizdir ve internet bağlantısı gerektirir.
```

---

## ٥. وشە قەدەغەکان — لە **هەموو** زمانەکان

| ❌ ئینگلیزی | ❌ کوردی | ❌ عەربی |
|---|---|---|
| best, top, #1, No.1, leading | باشترین، یەکەمین، پێشەنگ | الأفضل، الأول، الرائد |
| trusted, reliable, official | متمانەپێکراو، فەرمی | موثوق، الرسمي |
| fastest, largest, most modern | خێراترین، گەورەترین، مۆدێرنترین | الأسرع، الأكبر، الأحدث |
| award, app of the year | خەڵات، باشترین ئەپی ساڵ | جائزة، تطبيق العام |
| millions of users, 10K+ downloads | ملیۆنان بەکارهێنەر | ملايين المستخدمين |
| free, sale, discount, offer (لە title) | بەخۆڕایی، داشکاندن (لە title) | مجاني، خصم (لە title) |

> ڕستەی کۆن *"…together in one **trusted** platform"* بەهۆی وشەی **trusted**
> ڕەتکرایەوە.

---

## ٦. ئەسێتەکانی Play — نوێکراونەتەوە

| ئەسێت | ڕێڕەو | دۆخ |
|---|---|---|
| App icon 512×512 | `xwendngakan_app/play_store_assets/app_icon_512.png` | ✅ گۆڕدرا — ئەستێرەکەی لابرا |
| Feature graphic 1024×500 | `xwendngakan_app/play_store_assets/feature_graphic_1024x500.png` | ✅ گۆڕدرا — «Khwenden» |
| Screenshots | `xwendngakan_app/play_store_assets/screenshots/` | ✅ پاکن |
| بانەرەکانی ناو ئەپ | `banners/` | ✅ پاکن |

> ئایکۆنی کۆن ئەستێرەیەکی زێڕینی لەسەر گۆشەکەی بوو. Google بە ڕوونی دەڵێت ئایکۆن
> نابێت هیچ نیشانەیەکی *store performance or ranking* هەبێت — ئەستێرەی زێڕین
> دەکرێت وەک star rating یان خەڵات لێکبدرێتەوە.

---

## ٧. هۆکاری ڕیجێکتی دووبارە — ئەمانە بپشکنە

1. **هەموو زمانەکانی listing** — Play Console زمانی جیاواز وەک listingـی جیاواز
   هەڵدەسەنگێنێت. زۆرجار تەنها English ڕاست دەکرێتەوە و کوردی/عەربی بە دەقی
   کۆنەوە دەمێنێتەوە → دووبارە ڕیجێکت.
   شوێن: *Main store listing → دوگمەی زمان لە سەرەوە → هەر زمانێک بەجیا*
2. **App name** لە هەموو زمانەکان بگۆڕە بۆ `خوێندن` / `Khwenden`.
3. **Screenshots و Feature graphic** — نابێت هیچ دەقێکی وەک "Best" / "#1" /
   "باشترین" لەسەریان نووسرابێت.
4. **Promo video** (ئەگەر هەیە) — هەمان یاسا.
5. **Developer name** — «EU IT Experts» باشە.
6. **Store settings → tags** — تاگی نابەجێ زیاد مەکە.

---

## ٨. هەنگاوەکانی ناردنەوە بۆ پێداچوونەوە

1. Play Console → **Grow → Store presence → Main store listing**
2. بۆ **هەر زمانێک**: App name + short + full description بگۆڕە → **Save**
3. Icon و Feature graphic نوێ بار بکە (لە `play_store_assets/`)
4. ئەگەر build نوێ دەنێریت: `xwendngakan_app/pubspec.yaml` → `version` بەرز بکەرەوە
   (ئێستا `1.0.5+12` → `1.0.6+13`)
5. **Publishing overview → Send changes for review**
6. چاوەڕوانی ١–٧ ڕۆژ بکە.

> **ئایا appeal بکەین؟** نەخێر. دەقەکە بەڕاستی وشەی *trusted*ی تێدابوو، کەواتە
> بڕیارەکەیان ڕاستە و appeal ڕەت دەکرێتەوە. ڕاستکردنەوە + ناردنەوە خێراترە.

---

## ٩. چێکلیستی کۆتایی

- [ ] App name لە Play Console گۆڕدرا بۆ `خوێندن` / `Khwenden` — **هەموو زمانەکان**
- [ ] Full description گۆڕدرا — English
- [ ] Full description گۆڕدرا — کوردی
- [ ] Full description گۆڕدرا — عەربی
- [ ] Full description گۆڕدرا — تورکی
- [ ] Short description گۆڕدرا — هەموو زمانەکان
- [ ] Icon نوێ بار کرا (بێ ئەستێرە)
- [ ] Feature graphic نوێ بار کرا («Khwenden»)
- [ ] Screenshots پشکنین کران
- [ ] Send changes for review
