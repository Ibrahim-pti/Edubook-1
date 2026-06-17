<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'پشووی جەژنی قوربان',
                'description' => 'پشووی فەرمی دامودەزگاکانی هەرێمی کوردستان بەبۆنەی جەژنی قوربانەوە.',
                'date' => '2026-06-18',
                'duration_days' => 5,
                'category' => 'holiday',
                'icon' => 'brightness_high_rounded',
            ],
            [
                'title' => 'کۆتایی تاقیکردنەوەکانی وزاری پۆلی ١٢',
                'description' => 'کۆتایی هاتنی تاقیکردنەوەکانی خولی یەکەمی ئەزموونە گشتییەکانی پۆلی ١٢ ئامادەیی.',
                'date' => '2026-07-02',
                'duration_days' => 1,
                'category' => 'exam',
                'icon' => 'assignment_turned_in_rounded',
            ],
            [
                'title' => 'ڕاگەیاندنی نمرەکانی پۆلی ١٢',
                'description' => 'کاتی خەمڵێنراو بۆ ڕاگەیاندنی ئەنجامەکانی تاقیکردنەوەی وزاری پۆلی ١٢ لە لایەن وەزارەتی پەروەردەوە.',
                'date' => '2026-07-20',
                'duration_days' => 1,
                'category' => 'deadline',
                'icon' => 'analytics_rounded',
            ],
            [
                'title' => 'دەستپێکردنی پێشکەشکردن بۆ زانکۆلاین (کاتی مەزندەکراو)',
                'description' => 'دەستپێکردنی پرۆسەی پێشکەشکردن و هەڵبژاردنی بەشەکان لە زانکۆلاین بۆ دەرچووانی پۆلی ١٢.',
                'date' => '2026-08-15',
                'duration_days' => 10,
                'category' => 'deadline',
                'icon' => 'input_rounded',
            ],
            [
                'title' => 'تاقیکردنەوەکانی خولی دووەمی پۆلی ١٢',
                'description' => 'دەستپێکردنی تاقیکردنەوەکانی ئەزموونە گشتییەکانی خولی دووەم بۆ پۆلی ١٢ ئامادەیی.',
                'date' => '2026-09-01',
                'duration_days' => 8,
                'category' => 'exam',
                'icon' => 'menu_book_rounded',
            ],
            [
                'title' => 'دەستپێکردنی ساڵی نوێی خوێندنی قوتابخانەکان',
                'description' => 'دەستپێکردنی وەرزی یەکەمی خوێندنی قوتابخانەکانی هەرێمی کوردستان بۆ ساڵی ٢٠٢٦ - ٢٠٢٧.',
                'date' => '2026-09-15',
                'duration_days' => 1,
                'category' => 'exam',
                'icon' => 'school_rounded',
            ],
            [
                'title' => 'دەستپێکردنی وەرزی یەکەمی زانکۆ و پەیمانگاکان',
                'description' => 'دەستپێکردنی ساڵی خوێندنی نوێ لە زانکۆ و پەیمانگاکانی هەرێمی کوردستان بۆ قوتابیانی قۆناغی نوێ و کۆن.',
                'date' => '2026-10-01',
                'duration_days' => 1,
                'category' => 'exam',
                'icon' => 'account_balance_rounded',
            ],
            [
                'title' => 'پشووی ڕۆژی جەژنی لەدایکبوونی پێغەمبەر (د.خ)',
                'description' => 'پشووی فەرمی لە سەرجەم فەرمانگە و ناوەندەکانی خوێندن بەبۆنەی یادی مەولودەوە.',
                'date' => '2026-10-12',
                'duration_days' => 1,
                'category' => 'holiday',
                'icon' => 'star_rounded',
            ],
            [
                'title' => 'تاقیکردنەوەکانی نیوەی وەرزی یەکەمی قوتابخانەکان',
                'description' => 'تاقیکردنەوەی نووسینەکی نیوەی وەرزی یەکەم لە قوتابخانە بنەڕەتی و ئامادەییەکاندا.',
                'date' => '2026-11-15',
                'duration_days' => 7,
                'category' => 'exam',
                'icon' => 'assignment_rounded',
            ],
            [
                'title' => 'پشووی جەژنی لەدایکبوونی مەسیح (ع.س)',
                'description' => 'پشووی فەرمی بەبۆنەی جەژنی کریسمس و سەری ساڵەوە لە ناوەندەکانی خوێندن.',
                'date' => '2026-12-25',
                'duration_days' => 3,
                'category' => 'holiday',
                'icon' => 'ac_unit_rounded',
            ],
            [
                'title' => 'پشووی سەری ساڵی نوێی زایینی',
                'description' => 'پشووی فەرمی یەکی کانوونی دووەم بۆ پێشوازیکردن لە سەری ساڵی نوێ.',
                'date' => '2027-01-01',
                'duration_days' => 1,
                'category' => 'holiday',
                'icon' => 'celebration_rounded',
            ],
            [
                'title' => 'تاقیکردنەوەکانی کۆتایی وەرزی یەکەم (سەری ساڵ)',
                'description' => 'دەستپێکردنی تاقیکردنەوەکانی کۆتایی وەرزی یەکەم لە قوتابخانەکانی هەرێمی کوردستان.',
                'date' => '2027-01-03',
                'duration_days' => 10,
                'category' => 'exam',
                'icon' => 'note_alt_rounded',
            ],
            [
                'title' => 'پشووی وەرزی (نێوان دوو وەرزی خوێندن)',
                'description' => 'پشووی ناوەندی ساڵ بۆ سەرجەم قوتابخانە و ناوەندەکانی خوێندنی سەر بە وەزارەتی پەروەردە.',
                'date' => '2027-01-15',
                'duration_days' => 7,
                'category' => 'holiday',
                'icon' => 'hotel_rounded',
            ],
            [
                'title' => 'دەستپێکردنی وەرزی دووەمی خوێندن',
                'description' => 'دەستپێکردنی دەوامی وەرزی دووەمی خوێندن لە سەرجەم قوتابخانەکاندا.',
                'date' => '2027-01-23',
                'duration_days' => 1,
                'category' => 'exam',
                'icon' => 'play_arrow_rounded',
            ],
            [
                'title' => 'پشووی ڕۆژی جلی کوردی',
                'description' => 'بۆنەی نیشتمانیی ڕۆژی جلی کوردی لە قوتابخانەکان بە لەبەرکردنی جلی کوردی و سازکردنی چالاکی یاد دەکرێتەوە.',
                'date' => '2027-03-10',
                'duration_days' => 1,
                'category' => 'holiday',
                'icon' => 'accessibility_new_rounded',
            ],
            [
                'title' => 'پشووی جەژنی نەورۆز و سەری ساڵی کوردی',
                'description' => 'پشووی فەرمی لە سەرجەم فەرمانگە و ناوەندەکانی خوێندن بەبۆنەی نەورۆز و بەهارەوە.',
                'date' => '2027-03-20',
                'duration_days' => 4,
                'category' => 'holiday',
                'icon' => 'forest_rounded',
            ],
            [
                'title' => 'تاقیکردنەوەکانی کۆتایی وەرزی دووەم (کۆتایی ساڵ)',
                'description' => 'دەستپێکردنی تاقیکردنەوەی خولی یەکەمی کۆتایی ساڵ بۆ پۆلە ناکۆتاکان لە هەرێمی کوردستان.',
                'date' => '2027-05-10',
                'duration_days' => 10,
                'category' => 'exam',
                'icon' => 'check_circle_rounded',
            ],
            [
                'title' => 'دەستپێکردنی تاقیکردنەوەکانی وزاری پۆلی ١٢ (خولی یەکەم)',
                'description' => 'دەستپێکردنی ئەزموونە گشتییەکانی خولی یەکەمی پۆلی ١٢ ئامادەیی بە سەرجەم بەشەکانییەوە.',
                'date' => '2027-06-01',
                'duration_days' => 20,
                'category' => 'exam',
                'icon' => 'draw_rounded',
            ],
        ];

        foreach ($events as $event) {
            \App\Models\AcademicEvent::create($event);
        }
    }
}
