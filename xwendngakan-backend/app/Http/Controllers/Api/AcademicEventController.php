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
        $events = AcademicEvent::orderBy('date', 'asc')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description ?? '',
                'date' => $event->date->format('Y-m-d'),
                'duration_days' => $event->duration_days,
                'category' => $event->category,
                'icon' => $event->icon,
            ];
        });

        return response()->json(['data' => $events]);
    }
}
