<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Institution;
use Illuminate\Support\Facades\Http;

class TranslateInstitutions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:translate-institutions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translates existing departments and colleges in the database to English and Arabic.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $institutions = Institution::all();
        $this->info("Found {$institutions->count()} institutions. Translating...");

        $progress = $this->output->createProgressBar($institutions->count());
        $progress->start();

        foreach ($institutions as $inst) {
            $dirty = false;
            
            // 1. Translate Colleges
            if (!empty($inst->colleges)) {
                $cols = json_decode($inst->colleges, true);
                if (is_array($cols)) {
                    foreach ($cols as &$col) {
                        if (empty($col['name_en']) || empty($col['name_ar'])) {
                            $col['name_en'] = $this->translateText($col['name'], 'en');
                            $col['name_ar'] = $this->translateText($col['name'], 'ar');
                            $dirty = true;
                        }

                        if (!empty($col['depts']) && is_array($col['depts'])) {
                            foreach ($col['depts'] as &$dept) {
                                if (empty($dept['name_en']) || empty($dept['name_ar'])) {
                                    $dept['name_en'] = $this->translateText($dept['name'], 'en');
                                    $dept['name_ar'] = $this->translateText($dept['name'], 'ar');
                                    $dirty = true;
                                }
                            }
                        }
                    }
                    if ($dirty) {
                        $inst->colleges = json_encode($cols, JSON_UNESCAPED_UNICODE);
                    }
                }
            }

            // 2. Translate Depts
            if (!empty($inst->depts)) {
                // If it is a string array (newline separated), we need to convert it to JSON
                $deptsRaw = trim($inst->depts);
                if (!str_starts_with($deptsRaw, '[')) {
                    // It's a plain text list
                    $deptsList = array_filter(array_map('trim', explode("\n", $deptsRaw)));
                    $newDeptsJson = [];
                    foreach ($deptsList as $dname) {
                        $newDeptsJson[] = [
                            'ku' => $dname,
                            'en' => $this->translateText($dname, 'en'),
                            'ar' => $this->translateText($dname, 'ar')
                        ];
                    }
                    $inst->depts = json_encode($newDeptsJson, JSON_UNESCAPED_UNICODE);
                    $dirty = true;
                } else {
                    // It's already JSON. Check if we need to fill en/ar
                    $deptsJson = json_decode($deptsRaw, true);
                    if (is_array($deptsJson)) {
                        $deptsDirty = false;
                        foreach ($deptsJson as &$d) {
                            if (is_array($d)) {
                                if (empty($d['en']) || empty($d['ar'])) {
                                    $d['en'] = $this->translateText($d['ku'] ?? '', 'en');
                                    $d['ar'] = $this->translateText($d['ku'] ?? '', 'ar');
                                    $deptsDirty = true;
                                    $dirty = true;
                                }
                            }
                        }
                        if ($deptsDirty) {
                            $inst->depts = json_encode($deptsJson, JSON_UNESCAPED_UNICODE);
                        }
                    }
                }
            }

            if ($dirty) {
                // Also optionally translate nku/desc if empty
                if (!empty($inst->nku) && empty($inst->nen)) {
                    $inst->nen = $this->translateText($inst->nku, 'en');
                    $inst->nar = $this->translateText($inst->nku, 'ar');
                }
                if (!empty($inst->desc) && empty($inst->desc_en)) {
                    $inst->desc_en = $this->translateText($inst->desc, 'en');
                    $inst->desc_ar = $this->translateText($inst->desc, 'ar');
                }
                
                // Disable timestamps to avoid modifying updated_at unexpectedly
                $inst->timestamps = false;
                $inst->save();
            }

            $progress->advance();
            // Sleep to prevent rate limit
            usleep(200000); 
        }

        $progress->finish();
        $this->info("\nTranslation completed.");
    }

    private function translateText($text, $targetLang)
    {
        if (empty(trim($text))) return '';

        try {
            $response = Http::get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl'     => 'ckb', // Kurdish Sorani
                'tl'     => $targetLang,
                'dt'     => 't',
                'q'      => trim($text)
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data[0]) && is_array($data[0])) {
                    $translated = '';
                    foreach ($data[0] as $part) {
                        $translated .= $part[0] ?? '';
                    }
                    return $translated;
                }
            }
        } catch (\Exception $e) {
            // Ignore errors, return empty string
        }
        
        return '';
    }
}
