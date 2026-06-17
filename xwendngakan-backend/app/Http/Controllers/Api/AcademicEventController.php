<?php

namespace App\Http\Controllers\Api;

use App\Models\AcademicEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AcademicEventController
{
    public function index(): JsonResponse
    {
        $year = date('Y');
        $dbEvents = AcademicEvent::orderBy('date', 'asc')->get()->toArray();
        $apiEvents = [];

        try {
            $response = Http::timeout(3)->get("https://date.nager.at/api/v3/PublicHolidays/{$year}/IQ");
            if ($response->successful()) {
                $holidays = $response->json();
                if (is_array($holidays)) {
                    foreach ($holidays as $h) {
                        $apiEvents[] = [
                            'id' => null,
                            'title' => $h['localName'] ?? $h['name'],
                            'description' => $h['name'] ?? '',
                            'date' => $h['date'],
                            'duration_days' => 1,
                            'category' => 'holiday',
                            'icon' => 'celebration_rounded',
                            'created_at' => null,
                            'updated_at' => null,
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("Nager.at holidays API failed: " . $e->getMessage());
        }

        // Merge lists
        $merged = array_merge($dbEvents, $apiEvents);

        // Sort by date key ascending
        usort($merged, function ($a, $b) {
            $dateA = is_string($a['date']) ? $a['date'] : (isset($a['date']) ? $a['date']->format('Y-m-d') : '');
            $dateB = is_string($b['date']) ? $b['date'] : (isset($b['date']) ? $b['date']->format('Y-m-d') : '');
            return strcmp($dateA, $dateB);
        });

        // Format dates consistently as Y-m-d strings
        foreach ($merged as &$item) {
            if ($item['date'] instanceof \DateTimeInterface) {
                $item['date'] = $item['date']->format('Y-m-d');
            }
        }

        return response()->json(['data' => $merged]);
    }
}
