<?php

namespace App\Http\Controllers\Api;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController
{
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
            'history.*.role' => 'required|string|in:user,model,assistant',
            'history.*.message' => 'required|string',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        $message = $request->input('message');
        $history = $request->input('history', []);

        // Fetch institutions to build context
        $institutions = Institution::select('nku', 'type', 'city', 'phone', 'email', 'web', 'desc', 'tuition_plans')
            ->orderBy('nku', 'asc')
            ->get();

        $context = "لێره‌دا زانیاری هه‌موو قوتابخانه‌، په‌یمانگا، زانکۆ و ناوه‌نده‌کانی خوێندن له‌ کوردستان هه‌یه‌:\n\n";

        foreach ($institutions as $inst) {
            $context .= "ناوی دامەزراوە: " . $inst->nku . "\n";
            $context .= "جۆر: " . $this->translateType($inst->type) . "\n";
            $context .= "شار: " . $inst->city . "\n";
            if ($inst->web) $context .= "وێب سایت: " . $inst->web . "\n";
            if ($inst->phone) $context .= "تەلەفۆن: " . $inst->phone . "\n";
            if ($inst->email) $context .= "ئیمەیڵ: " . $inst->email . "\n";
            if ($inst->desc) $context .= "ڕوونکردنەوە: " . strip_tags($inst->desc) . "\n";

            if ($inst->tuition_plans && is_array($inst->tuition_plans)) {
                $context .= "بەشەکان و کرێی خوێندن:\n";
                foreach ($inst->tuition_plans as $plan) {
                    $dept = $plan['dept'] ?? '';
                    $fee = $plan['fee'] ?? 'دیاری نەکراوە';
                    $discount = $plan['discount'] ?? '';
                    $context .= " - بەشی " . $dept . ": کرێی ساڵانە " . $fee . ($discount ? " (داشکاندن: " . $discount . ")" : "") . "\n";
                }
            }
            $context .= "-----------------------------------\n";
        }

        $systemInstruction = "You are Edubook AI Assistant, a friendly and expert academic advisor for students in Kurdistan. "
            . "Your job is to help students find suitable schools, colleges, universities, and departments based ONLY on the provided directory.\n\n"
            . "Directory of Institutions in Kurdistan:\n"
            . $context . "\n\n"
            . "Rules:\n"
            . "1. Be polite, friendly, and helpful.\n"
            . "2. Answer the user's questions in Kurdish Sorani. Do not speak English or Arabic unless requested.\n"
            . "3. Use only the provided directory of institutions to answer questions. If the information is not in the directory, politely tell the user that you don't have this information in your database. Do not make up any information.\n"
            . "4. Provide details such as tuition fees, discounts, departments, cities, websites, and contact numbers if asked and available.\n"
            . "5. Format your response beautifully in clean Markdown. Use bolding, bullet points, and numbered lists where appropriate to make it readable on mobile.\n"
            . "6. Keep answers concise, clear, and relevant.";

        if ($apiKey) {
            // Use Gemini API
            $contents = [];
            foreach ($history as $turn) {
                $role = ($turn['role'] === 'user') ? 'user' : 'model';
                $contents[] = [
                    'role' => $role,
                    'parts' => [
                        ['text' => $turn['message']]
                    ]
                ];
            }

            // Add current user message
            $contents[] = [
                'role' => 'user',
                'parts' => [
                    ['text' => $message]
                ]
            ];

            try {
                $response = Http::timeout(30)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                        'contents' => $contents,
                        'systemInstruction' => [
                            'parts' => [
                                ['text' => $systemInstruction]
                            ]
                        ]
                    ]);

                if ($response->failed()) {
                    Log::error("Gemini API request failed: " . $response->body());
                    return response()->json([
                        'success' => false,
                        'message' => 'خزمەتگوزاری یاریدەدەری زیرەک لە ئێستادا بەردەست نییە، تکایە دواتر هەوڵبدەرەوە.',
                    ], 500);
                }

                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

                if (empty($reply)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'هیچ وەڵامێک وەرنەگیرا.',
                    ], 500);
                }

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                ]);

            } catch (\Exception $e) {
                Log::error("Gemini API exception: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'هەڵەیەک لە پەیوەندی یاریدەدەری زیرەکدا ڕوویدا.',
                ], 500);
            }
        } else {
            // Use Pollinations AI (Free fallback)
            $messages = [];
            $messages[] = [
                'role' => 'system',
                'content' => $systemInstruction
            ];

            foreach ($history as $turn) {
                $role = ($turn['role'] === 'user') ? 'user' : 'assistant';
                $messages[] = [
                    'role' => $role,
                    'content' => $turn['message']
                ];
            }

            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            try {
                $response = Http::timeout(30)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post("https://text.pollinations.ai/", [
                        'messages' => $messages,
                        'model' => 'openai'
                    ]);

                if ($response->failed()) {
                    Log::error("Pollinations AI API request failed: " . $response->body());
                    return response()->json([
                        'success' => false,
                        'message' => 'خزمەتگوزاری یاریدەدەری زیرەک لە ئێستادا بەردەست نییە، تکایە دواتر هەوڵبدەرەوە.',
                    ], 500);
                }

                $reply = $response->body();

                if (empty($reply)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'هیچ وەڵامێک وەرنەگیرا لە یاریدەدەری ناوخۆیی.',
                    ], 500);
                }

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                ]);

            } catch (\Exception $e) {
                Log::error("Pollinations AI API exception: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'هەڵەیەک لە پەیوەندی یاریدەدەری ناوخۆییدا ڕوویدا.',
                ], 500);
            }
        }
    }

    private function translateType(string $type): string
    {
        $types = [
            'university' => 'زانکۆ (University)',
            'institute' => 'ئینستیتیوت (Institute)',
            'school' => 'قوتابخانە (School)',
            'kindergarten' => 'باخچەی منداڵان (Kindergarten)',
            'language_center' => 'سەنتەری زمان (Language Center)',
        ];
        return $types[$type] ?? $type;
    }
}
