class AppConstants {
  AppConstants._();

  // === API ===
  static const String baseUrl = 'https://edubook-iq.com/api';
  // static const String baseUrl = 'http://edubook-iq.com/api'; // Use this if SSL (HTTPS) is not yet active
  // static const String baseUrl = 'http://edubook-iq.com/public/api'; // Try this if /api 404s
  // For Local: 'http://192.168.7.163:8001/api'
  // For Android Emulator: 'http://10.0.2.2:8001/api'

  static const Duration connectTimeout = Duration(seconds: 15);
  static const Duration receiveTimeout = Duration(seconds: 30);

  // === STORAGE KEYS ===
  static const String tokenKey = 'auth_token';
  static const String userKey = 'user_data';
  static const String langKey = 'app_language';
  static const String langSelectedKey = 'lang_selected';
  static const String themeKey = 'app_theme';
  static const String onboardingKey = 'onboarding_done';
  static const String roleSelectedKey = 'role_selected';
  static const String selectedRoleKey = 'selected_role';
  static const String favoritesKey = 'favorites';

  // === DEFAULTS ===
  static const String defaultLang = 'ku';
  static const int pageSize = 15;

  // === LANGUAGES ===
  static const Map<String, Map<String, String>> languages = {
    'ku': {'name': 'کوردی (سۆرانی)', 'flag': '❤️☀️💚', 'dir': 'rtl'},
    'kbd': {'name': 'کوردی (بادینی)', 'flag': '❤️☀️💚', 'dir': 'rtl'},
    'ar': {'name': 'العربية', 'flag': '🇮🇶', 'dir': 'rtl'},
    'en': {'name': 'ئینگلیزی', 'flag': '🇬🇧', 'dir': 'ltr'},
  };

  // === ANIMATION DURATIONS ===
  static const Duration fast = Duration(milliseconds: 200);
  static const Duration medium = Duration(milliseconds: 350);
  static const Duration slow = Duration(milliseconds: 600);
  static const Duration extraSlow = Duration(milliseconds: 1000);

  // === BORDER RADIUS ===
  static const double radiusSm = 8.0;
  static const double radiusMd = 12.0;
  static const double radiusLg = 16.0;
  static const double radiusXl = 24.0;
  static const double radiusXxl = 32.0;
  static const double radiusFull = 100.0;

  // === SPACING ===
  static const double spacingXs = 4.0;
  static const double spacingSm = 8.0;
  static const double spacingMd = 16.0;
  static const double spacingLg = 24.0;
  static const double spacingXl = 32.0;
  static const double spacingXxl = 48.0;

  // === INSTITUTION TYPES ===
  static const Map<String, Map<String, String>> institutionTypes = {
    'university': {
      'ku': 'زانکۆ',
      'ar': 'جامعة',
      'en': 'University',
      'tr': 'Üniversite',
      'emoji': '🎓',
    },
    'institute': {
      'ku': 'ئینستیتیوت',
      'ar': 'معهد',
      'en': 'Institute',
      'tr': 'Enstitü',
      'emoji': '🏛️',
    },
    'school': {
      'ku': 'قوتابخانە',
      'ar': 'مدرسة',
      'en': 'School',
      'tr': 'Okul',
      'emoji': '🏫',
    },
    'kindergarten': {
      'ku': 'باخچەی منداڵان',
      'ar': 'روضة',
      'en': 'Kindergarten',
      'tr': 'Anaokulu',
      'emoji': '🌱',
    },
    'language_center': {
      'ku': 'سەنتەری زمان',
      'ar': 'مركز لغات',
      'en': 'Language Center',
      'tr': 'Dil Merkezi',
      'emoji': '💬',
    },
  };

  // === FILTER CITIES (display-name → DB value map) ===
  // DB stores Kurdish names; these are the exact values used in WHERE clause
  static const List<String> filterCityApiValues = [
    'هەولێر',
    'سلێمانی',
    'دهۆک',
    'هەڵەبجە',
    'کەرکوک',
  ];

  // === CITY TRANSLATIONS ===
  static const Map<String, Map<String, String>> cityNames = {
    'هەولێر':       {'en': 'Erbil',          'ar': 'أربيل'},
    'سلێمانی':      {'en': 'Sulaymaniyah',   'ar': 'السليمانية'},
    'دهۆک':         {'en': 'Duhok',          'ar': 'دهوك'},
    'زاخۆ':         {'en': 'Zakho',          'ar': 'زاخو'},
    'ئامێدی':       {'en': 'Amedi',          'ar': 'العمادية'},
    'سیمێل':        {'en': 'Simele',         'ar': 'سيميل'},
    'شێخان':        {'en': 'Shekhan',        'ar': 'شيخان'},
    'دیانا':        {'en': 'Diana',          'ar': 'ديانا'},
    'چۆمان':        {'en': 'Choman',         'ar': 'جومان'},
    'سۆران':        {'en': 'Soran',          'ar': 'سوران'},
    'هەڵەبجە':      {'en': 'Halabja',        'ar': 'حلبجة'},
    'رانیە':        {'en': 'Ranya',          'ar': 'رانية'},
    'کەلار':        {'en': 'Kalar',          'ar': 'كلار'},
    'قلادزێ':       {'en': 'Qaladze',        'ar': 'قلادزة'},
    'دوکان':        {'en': 'Dokan',          'ar': 'دوكان'},
    'دەربەندیخان':  {'en': 'Darbandikhan',   'ar': 'دربنديخان'},
    'چەمچەماڵ':     {'en': 'Chamchamal',     'ar': 'جمجمال'},
    'شارەزووری':    {'en': 'Sharazur',       'ar': 'شهرزور'},
    'پێنجوێن':      {'en': 'Penjwen',        'ar': 'بنجوين'},
    'سەید سادق':    {'en': 'Said Sadiq',     'ar': 'سيد صادق'},
    'کفری':         {'en': 'Kifri',          'ar': 'كفري'},
    'کەرکووک':      {'en': 'Kirkuk',         'ar': 'كركوك'},
    'کەرکوک':       {'en': 'Kirkuk',         'ar': 'كركوك'},
    'بەغداد':       {'en': 'Baghdad',        'ar': 'بغداد'},
    'مووسڵ':        {'en': 'Mosul',          'ar': 'الموصل'},
    'بەسرە':        {'en': 'Basra',          'ar': 'البصرة'},
    'نەجەف':        {'en': 'Najaf',          'ar': 'النجف'},
    'کەربەلا':      {'en': 'Karbala',        'ar': 'كربلاء'},
    'حیللە':        {'en': 'Hilla',          'ar': 'الحلة'},
    'سامەراء':      {'en': 'Samarra',        'ar': 'سامراء'},
    'تکریت':        {'en': 'Tikrit',         'ar': 'تكريت'},
    'رمادی':        {'en': 'Ramadi',         'ar': 'الرمادي'},
    'فەللووجە':     {'en': 'Fallujah',       'ar': 'الفلوجة'},
    'نەسیریە':      {'en': 'Nasiriyah',      'ar': 'الناصرية'},
    'عەماره':       {'en': 'Amarah',         'ar': 'العمارة'},
    'کووت':         {'en': 'Kut',            'ar': 'الكوت'},
    'دیوانیە':      {'en': 'Diwaniyah',      'ar': 'الديوانية'},
    'بعقووبە':      {'en': 'Baqubah',        'ar': 'بعقوبة'},
    'سینجار':       {'en': 'Sinjar',         'ar': 'سنجار'},
    'تەلاعەفەر':    {'en': 'Tal Afar',       'ar': 'تلعفر'},
    'دوزەخوڕماتو':  {'en': 'Tuz Khurmatu',   'ar': 'طوز خورماتو'},
    'دیالی':        {'en': 'Diyala',         'ar': 'ديالى'},
    'رووتبە':       {'en': 'Rutba',          'ar': 'الرطبة'},
    'قائم':         {'en': 'Qaim',           'ar': 'القائم'},
    'عەلی گەرب':    {'en': 'Ali al-Gharbi',  'ar': 'علي الغربي'},
    'مەیسان':       {'en': 'Maysan',         'ar': 'ميسان'},
    'واسط':         {'en': 'Wasit',          'ar': 'واسط'},
    'صلاح الدین':   {'en': 'Salah al-Din',   'ar': 'صلاح الدين'},
    'ئەنبار':       {'en': 'Anbar',          'ar': 'الأنبار'},
    'نینەوا':       {'en': 'Nineveh',        'ar': 'نينوى'},
  };

  static String localizedCityName(String kuName, String lang) {
    if (lang == 'ku' || lang == 'kbd') return kuName;
    final tr = cityNames[kuName];
    if (tr == null) return kuName;
    return tr[lang] ?? kuName;
  }

  // === CITIES ===
  static const List<String> iraqiCities = [
    // کوردستان
    'هەولێر',
    'سلێمانی',
    'دهۆک',
    'زاخۆ',
    'ئامێدی',
    'سیمێل',
    'شێخان',
    'دیانا',
    'چۆمان',
    'سۆران',

    'کەرکووک',
    'هەڵەبجە',
    'رانیە',
    'کەلار',
    'قلادزێ',
    'دوکان',
    'دەربەندیخان',
    'کفری',
    'چەمچەماڵ',
    'شارەزووری',
    'پێنجوێن',
    'سەید سادق',
    'دوزەخوڕماتو',
    // عراق
    'بەغداد',
    'مووسڵ',
    'بەسرە',
    'نەجەف',
    'کەربەلا',
    'حیللە',
    'سامەراء',
    'تکریت',
    'رمادی',
    'فەللووجە',
    'نەسیریە',
    'عەماره',
    'کووت',
    'دیوانیە',
    'دیالی',
    'بعقووبە',
    'رووتبە',
    'قائم',
    'عەلی گەرب',
    'مەیسان',
    'واسط',
    'صلاح الدین',
    'ئەنبار',
    'نینەوا',
    'سینجار',
    'تەلاعەفەر',
  ];
}
