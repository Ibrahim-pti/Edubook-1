<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Seeder;

/**
 * Paytakht Technical Institute — Private (Erbil).
 *
 * Data taken from the institute's own site, https://pti.edu.krd.
 * Descriptions are deliberately factual: Google Play's Metadata policy bars
 * promotional or ranking claims anywhere the app shows content, so no
 * "باشترین"/"leading"/"best" wording here.
 *
 * Run with:  php artisan db:seed --class=PaytakhtInstituteSeeder
 * Idempotent — re-running updates the existing row instead of duplicating it.
 */
class PaytakhtInstituteSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['ku' => 'دەرمانسازی',            'en' => 'Pharmacy'],
            ['ku' => 'شیکاری نەخۆشییەکان',    'en' => 'Medical Laboratory Analysis'],
            ['ku' => 'پەرستاری',              'en' => 'Nursing'],
            ['ku' => 'پەرستاری فریاکەوتن',    'en' => 'Emergency Nursing'],
            ['ku' => 'تەکنیکی سڕکردن',        'en' => 'Anesthesia Technology'],
            ['ku' => 'ژمێریاری',              'en' => 'Accounting'],
            ['ku' => 'کۆمپیوتەر و نێتوۆرک',   'en' => 'Computer and Network'],
            ['ku' => 'زمانی ئینگلیزی',        'en' => 'English Language'],
            ['ku' => 'کارگێڕی کار',           'en' => 'Business Administration'],
            ['ku' => 'کارەبا و میکانیک',      'en' => 'Electricity and Mechanics'],
            ['ku' => 'ڕێنمایی کشتوکاڵ',       'en' => 'Agricultural Guidance'],
        ];

        $colleges = json_encode([[
            'name'        => 'بەشەکان',
            'departments' => array_column($departments, 'ku'),
        ]], JSON_UNESCAPED_UNICODE);

        Institution::updateOrCreate(
            ['nen' => 'Paytakht Technical Institute'],
            [
                'nku'  => 'پەیمانگەی تەکنیکی پایتەخت - تایبەت',
                'nkbd' => 'پەیمانگەها تەکنیکی یا پایتەخت - تایبەت',
                'nar'  => 'معهد بايتخت التقني - الأهلي',

                'type'    => 'inst2',
                'country' => 'عێراق',
                'city'    => 'هەولێر',
                'addr'    => 'هەولێر — پشت نەخۆشخانەی ڕۆژئاوا',
                'lat'     => 36.1587891,
                'lng'     => 43.9701555,

                'phone' => '07508567733',
                'email' => 'ibrahim.ahmed@pti.edu.krd',
                'web'   => 'https://pti.edu.krd',

                'fb' => 'https://www.facebook.com/paytakht.inst/',
                'ig' => 'https://www.instagram.com/paitaxt_technical_institute',
                'wa' => '9647508567733',

                'desc' => 'پەیمانگەی تەکنیکی پایتەخت پەیمانگایەکی تایبەتی دوو ساڵییە لە شاری هەولێر، '
                    . 'لە ساڵی ٢٠١٥ مۆڵەتی پێدراوە. یازدە بەشی جیاواز پێشکەش دەکات لە بوارەکانی '
                    . 'پزیشکی، تەکنەلۆژیا، کارگێڕی و کشتوکاڵدا.',
                'desc_kbd' => 'پەیمانگەها تەکنیکی یا پایتەخت پەیمانگەهەکا تایبەتە یا دو سالان ل باژێرێ هەولێرێ، '
                    . 'ل سالا ٢٠١٥ دەستهەلات هاتیە دان. یازدە بەشێن جودا پێشکێش دکەت.',
                'desc_ar' => 'معهد بايتخت التقني هو معهد أهلي مدته سنتان في مدينة أربيل، مُجاز منذ عام ٢٠١٥. '
                    . 'يقدّم أحد عشر قسماً في مجالات الطب والتكنولوجيا والإدارة والزراعة.',
                'desc_en' => 'Paytakht Technical Institute is a private two-year institute in Erbil, licensed in 2015. '
                    . 'It offers eleven departments across medical, technology, administration and agriculture fields.',

                'colleges' => $colleges,
                'level'    => 'دوو ساڵ',

                'logo' => '/storage/institutions/paytakht-logo.png',

                'founded_year'   => 2015,
                'students_count' => 1703,
                'manager_name'   => null,

                'approved' => true,
            ]
        );

        $this->command->info('✓ پەیمانگەی تەکنیکی پایتەخت زیادکرا/نوێکرایەوە.');
    }
}
