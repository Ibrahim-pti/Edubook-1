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
            ['name' => 'هەولێر',        'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'سلێمانی',       'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'دهۆک',          'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'زاخۆ',          'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'ئامێدی',        'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'سیمێل',         'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'شێخان',         'governorate' => 'دهۆک',       'country' => 'کوردستان'],
            ['name' => 'دیانا',         'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'چۆمان',         'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'سۆران',         'governorate' => 'هەولێر',    'country' => 'کوردستان'],
            ['name' => 'هەڵەبجە',       'governorate' => 'هەڵەبجە',   'country' => 'کوردستان'],
            ['name' => 'رانیە',         'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'کەلار',         'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'قلادزێ',        'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'دوکان',         'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'دەربەندیخان',   'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'چەمچەماڵ',      'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'شارەزووری',     'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'پێنجوێن',       'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'سەید سادق',     'governorate' => 'سلێمانی',   'country' => 'کوردستان'],
            ['name' => 'کفری',          'governorate' => 'کەرکووک',    'country' => 'عێراق'],
            ['name' => 'کەرکووک',       'governorate' => 'کەرکووک',    'country' => 'عێراق'],
            // عێراق
            ['name' => 'بەغداد',        'governorate' => 'بەغداد',     'country' => 'عێراق'],
            ['name' => 'مووسڵ',         'governorate' => 'نەینەوا',    'country' => 'عێراق'],
            ['name' => 'بەسرە',         'governorate' => 'بەسرە',      'country' => 'عێراق'],
            ['name' => 'نەجەف',         'governorate' => 'نەجەف',      'country' => 'عێراق'],
            ['name' => 'کەربەلا',       'governorate' => 'کەربەلا',    'country' => 'عێراق'],
            ['name' => 'حیللە',         'governorate' => 'بابل',       'country' => 'عێراق'],
            ['name' => 'سامەراء',       'governorate' => 'سامەراء',    'country' => 'عێراق'],
            ['name' => 'تکریت',         'governorate' => 'سەلاحەدین',  'country' => 'عێراق'],
            ['name' => 'رمادی',         'governorate' => 'ئەنبار',     'country' => 'عێراق'],
            ['name' => 'فەللووجە',      'governorate' => 'ئەنبار',     'country' => 'عێراق'],
            ['name' => 'نەسیریە',       'governorate' => 'ذی قار',     'country' => 'عێراق'],
            ['name' => 'عەماره',        'governorate' => 'مەیسان',     'country' => 'عێراق'],
            ['name' => 'کووت',          'governorate' => 'واسط',       'country' => 'عێراق'],
            ['name' => 'دیوانیە',       'governorate' => 'قادسیە',     'country' => 'عێراق'],
            ['name' => 'بعقووبە',       'governorate' => 'دیالى',      'country' => 'عێراق'],
            ['name' => 'سینجار',        'governorate' => 'نەینەوا',    'country' => 'عێراق'],
            ['name' => 'تەلاعەفەر',     'governorate' => 'نەینەوا',    'country' => 'عێراق'],
            ['name' => 'دوزەخوڕماتو',   'governorate' => 'سەلاحەدین',  'country' => 'عێراق'],
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
