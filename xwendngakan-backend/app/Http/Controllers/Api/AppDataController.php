<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstitutionType;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class AppDataController extends Controller
{
    /**
     * Get all active institution types.
     */
    public function institutionTypes(): JsonResponse
    {
        $types = InstitutionType::active()->ordered()->get(['key', 'name', 'name_en', 'name_ar', 'emoji', 'icon']);
        
        return response()->json([
            'success' => true,
            'data'    => $types,
        ]);
    }

    /**
     * Unified app data — single endpoint for types + all institution form data.
     */
    public function appData(): JsonResponse
    {
        $types = InstitutionType::active()->ordered()->get(['key', 'name', 'name_en', 'name_ar', 'emoji', 'icon']);

        $countries = [
            ['key' => 'کوردستان', 'name' => 'کوردستان', 'name_en' => 'Kurdistan', 'name_ar' => 'كردستان'],
            ['key' => 'عێراق',    'name' => 'عێراق',    'name_en' => 'Iraq',       'name_ar' => 'العراق'],
        ];

        $cities = [
            // هەرێمی کوردستان
            ['name' => 'هەولێر',        'name_en' => 'Erbil',           'name_ar' => 'أربيل',          'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'سلێمانی',       'name_en' => 'Sulaymaniyah',    'name_ar' => 'السليمانية',     'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'دهۆک',          'name_en' => 'Duhok',           'name_ar' => 'دهوك',           'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'زاخۆ',          'name_en' => 'Zakho',           'name_ar' => 'زاخو',           'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'ئامێدی',        'name_en' => 'Amedi',           'name_ar' => 'العمادية',       'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'سیمێل',         'name_en' => 'Simele',          'name_ar' => 'سيميل',          'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'شێخان',         'name_en' => 'Shekhan',         'name_ar' => 'شيخان',          'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'دیانا',         'name_en' => 'Diana',           'name_ar' => 'ديانا',          'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'چۆمان',         'name_en' => 'Choman',          'name_ar' => 'جومان',          'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'سۆران',         'name_en' => 'Soran',           'name_ar' => 'سوران',          'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'هەڵەبجە',       'name_en' => 'Halabja',         'name_ar' => 'حلبجة',          'governorate' => 'هەڵەبجە',   'country' => 'کوردستان'],
            ['name' => 'رانیە',         'name_en' => 'Ranya',           'name_ar' => 'رانية',          'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'کەلار',         'name_en' => 'Kalar',           'name_ar' => 'كلار',           'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'قلادزێ',        'name_en' => 'Qaladze',         'name_ar' => 'قلادزة',         'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'دوکان',         'name_en' => 'Dokan',           'name_ar' => 'دوكان',          'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'دەربەندیخان',   'name_en' => 'Darbandikhan',    'name_ar' => 'دربنديخان',      'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'چەمچەماڵ',      'name_en' => 'Chamchamal',      'name_ar' => 'جمجمال',         'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'شارەزووری',     'name_en' => 'Sharazur',        'name_ar' => 'شهرزور',         'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'پێنجوێن',       'name_en' => 'Penjwen',         'name_ar' => 'بنجوين',         'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'سەید سادق',     'name_en' => 'Said Sadiq',      'name_ar' => 'سيد صادق',       'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'کفری',          'name_en' => 'Kifri',           'name_ar' => 'كفري',           'governorate' => 'کەرکووک',    'country' => 'عێراق'],
            ['name' => 'کەرکووک',       'name_en' => 'Kirkuk',          'name_ar' => 'كركوك',          'governorate' => 'کەرکووک',    'country' => 'عێراق'],
            // عێراق
            ['name' => 'بەغداد',        'name_en' => 'Baghdad',         'name_ar' => 'بغداد',          'governorate' => 'بەغداد',     'country' => 'عێراق'],
            ['name' => 'مووسڵ',         'name_en' => 'Mosul',           'name_ar' => 'الموصل',         'governorate' => 'نەینەوا',    'country' => 'عێراق'],
            ['name' => 'بەسرە',         'name_en' => 'Basra',           'name_ar' => 'البصرة',         'governorate' => 'بەسرە',      'country' => 'عێراق'],
            ['name' => 'نەجەف',         'name_en' => 'Najaf',           'name_ar' => 'النجف',          'governorate' => 'نەجەف',      'country' => 'عێراق'],
            ['name' => 'کەربەلا',       'name_en' => 'Karbala',         'name_ar' => 'كربلاء',         'governorate' => 'کەربەلا',    'country' => 'عێراق'],
            ['name' => 'حیللە',         'name_en' => 'Hilla',           'name_ar' => 'الحلة',          'governorate' => 'بابل',       'country' => 'عێراق'],
            ['name' => 'سامەراء',       'name_en' => 'Samarra',         'name_ar' => 'سامراء',         'governorate' => 'سامەراء',    'country' => 'عێراق'],
            ['name' => 'تکریت',         'name_en' => 'Tikrit',          'name_ar' => 'تكريت',          'governorate' => 'سەلاحەدین',  'country' => 'عێراق'],
            ['name' => 'رمادی',         'name_en' => 'Ramadi',          'name_ar' => 'الرمادي',        'governorate' => 'ئەنبار',     'country' => 'عێراق'],
            ['name' => 'فەللووجە',      'name_en' => 'Fallujah',        'name_ar' => 'الفلوجة',        'governorate' => 'ئەنبار',     'country' => 'عێراق'],
            ['name' => 'نەسیریە',       'name_en' => 'Nasiriyah',       'name_ar' => 'الناصرية',       'governorate' => 'ذی قار',     'country' => 'عێراق'],
            ['name' => 'عەماره',        'name_en' => 'Amarah',          'name_ar' => 'العمارة',        'governorate' => 'مەیسان',     'country' => 'عێراق'],
            ['name' => 'کووت',          'name_en' => 'Kut',             'name_ar' => 'الكوت',          'governorate' => 'واسط',       'country' => 'عێراق'],
            ['name' => 'دیوانیە',       'name_en' => 'Diwaniyah',       'name_ar' => 'الديوانية',      'governorate' => 'قادسیە',     'country' => 'عێراق'],
            ['name' => 'بعقووبە',       'name_en' => 'Baqubah',         'name_ar' => 'بعقوبة',         'governorate' => 'دیالى',      'country' => 'عێراق'],
            ['name' => 'سینجار',        'name_en' => 'Sinjar',          'name_ar' => 'سنجار',          'governorate' => 'نەینەوا',    'country' => 'عێراق'],
            ['name' => 'تەلاعەفەر',     'name_en' => 'Tal Afar',        'name_ar' => 'تلعفر',          'governorate' => 'نەینەوا',    'country' => 'عێراق'],
            ['name' => 'دوزەخوڕماتو',   'name_en' => 'Tuz Khurmatu',    'name_ar' => 'طوز خورماتو',    'governorate' => 'سەلاحەدین',  'country' => 'عێراق'],
        ];

        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get()
            ->map(fn ($b) => [
                'id'          => $b->id,
                'title'       => $b->title,
                'subtitle'    => $b->subtitle,
                'tag'         => $b->tag,
                'image_url'   => $b->image_url,
                'color_start' => $b->color_start,
                'color_end'   => $b->color_end,
            ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'types'     => $types,
                'countries' => $countries,
                'cities'    => $cities,
                'banners'   => $banners,
            ],
        ]);
    }
}
