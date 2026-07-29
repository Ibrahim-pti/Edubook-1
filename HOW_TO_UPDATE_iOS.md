# چۆن ئەپدەیتی ئەپ بنێرم بۆ App Store (ڕێنمایی هەمیشەیی)

> هەر جارێک دەتەوێت گۆڕانکاری/ئەپدەیت بنێریت، ئەم هەنگاوانە بکە بە ڕیزبەندی.
> ئەپ: **خوێندن / Khwenden** · Bundle ID: `com.pti.edubook`
> (Bundle ID هەرگیز مەگۆڕە — ناوی پیشاندان `CFBundleDisplayName`ـە، نەک ئەمە)

---

## هەنگاو ١ — گۆڕانکارییەکانت بکە
کۆدەکە بگۆڕە / تایبەتمەندی نوێ زیاد بکە، هتد.

## هەنگاو ٢ — ژمارەی ڤێرژن بەرز بکەرەوە ⚠️ (زۆر گرنگ)
لە فایلی `xwendngakan_app/pubspec.yaml`، دێڕی `version` بگۆڕە:

```
version: 1.0.5+7
         ↑     ↑
         │     └── Build Number — دەبێت HÆR جارێک +1 زیاد بکات (7, 8, 9...)
         └──────── Version Number — بۆ گۆڕانکاری گەورە (1.0.5, 1.1.0...)
```

**یاسا:**
- **Build number** (دوای `+`): دەبێت **هەمیشە زیاد بکات**. ئەگەر زیاد نەکات، Apple upload ڕەت دەکاتەوە.
- **Version number** (پێش `+`): بۆ ئەپدەیتی نوێ بەرزی بکەرەوە (نموونە: 1.0.4 → 1.0.5).

## هەنگاو ٣ — Build دروست بکە
لە تەرمیناڵ:
```bash
cd /Users/ibrahimpti/Desktop/Edubook-1/xwendngakan_app
flutter clean
flutter pub get
flutter build ipa --release
```
> `flutter clean` گرنگە ئەگەر پێشتر لەسەر Simulator کارت کردبێت (بۆ ئەوەی کێشەی "Invalid executable" نەیەت).

## هەنگاو ٤ — بیبارە بۆ App Store Connect
```bash
open build/ios/archive/Runner.xcarchive
```
لە Xcode Organizer:
1. ئەرشیڤی نوێ هەڵبژێرە.
2. **Distribute App** → **App Store Connect** → **Upload** → Next...→ **Upload**.
3. چاوەڕێ بکە تا "successfully uploaded" → **Done**.
4. (warning ـی dSYM گرنگ نییە.)

## هەنگاو ٥ — ڤێرژنی نوێ دروست بکە لە App Store Connect
1. بڕۆ بۆ [appstoreconnect.apple.com](https://appstoreconnect.apple.com) → ئەپەکە.
2. لای چەپ، لەتەنیشت **iOS App**، کلیک لە **(+)** → **Add Version**.
3. ژمارەی ڤێرژنی نوێ بنووسە (نموونە: `1.0.5`) → **Create**.

## هەنگاو ٦ — زانیارییەکان نوێ بکەرەوە
لە پەڕەی ڤێرژنی نوێ:
- **What's New in This Version** (پێویستە بۆ ئەپدەیت) — بنووسە چی نوێ کراوە، نموونە:
  ```
  - Bug fixes and performance improvements
  - New features added
  ```
- **Screenshots / Description / Keywords** — ئەگەر نەگۆڕاون، وەک خۆیان دەمێننەوە (پێویست نییە بیانگۆڕیت).

## هەنگاو ٧ — Build هەڵبژێرە
- بەشی **Build** → **Add Build** → buildـە نوێیەکە هەڵبژێرە.
- (١٥–٣٠ خولەک چاوەڕێ بکە ئەگەر هێشتا "Processing" ـە.)

## هەنگاو ٨ — بینێرە
- **Save** → **Add for Review** → **Submit for Review** 🚀

---

## 🔑 خاڵە گرنگەکان
| شت | یاسا |
|----|------|
| **Build number** (`+N`) | هەمیشە +1 زیاد بکە، هەر جارێک |
| **flutter clean** | بیکە ئەگەر لەسەر Simulator کارت کردبووە |
| **What's New** | بۆ هەر ئەپدەیتێک پێویستە |
| **Demo account** | دڵنیابە هەمیشە کاردەکات بۆ App Review |
| **Screenshots/Desc** | تەنها ئەگەر گۆڕان، نوێیان بکەرەوە |

## ⏱️ ماوەی پێداچوونەوە
- ٢٤–٤٨ کاتژمێر. دوای پەسەندکردن، ئەگەر "Automatically release" هەڵبژاردبێت، خۆکار دەردەکەوێت.
