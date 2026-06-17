<?php

namespace Tests\Feature;

use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_falls_back_to_pollinations_ai_if_api_key_not_configured(): void
    {
        // Ensure GEMINI_API_KEY is not set
        putenv('GEMINI_API_KEY=');

        // Create a mock institution
        Institution::create([
            'nku' => 'Test Uni',
            'type' => 'university',
            'city' => 'Slemani',
            'tuition_plans' => [
                ['dept' => 'Computer', 'fee' => '1000$']
            ],
            'approved' => true,
        ]);

        // Fake the Pollinations AI API call
        Http::fake([
            'https://text.pollinations.ai/' => Http::response('This is the mock reply from Pollinations.', 200)
        ]);

        $response = $this->postJson('/api/ai/chat', [
            'message' => 'Which university has Computer?',
            'history' => [
                ['role' => 'user', 'message' => 'Hi'],
                ['role' => 'model', 'message' => 'Hello, I am your advisor.'],
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'reply' => 'This is the mock reply from Pollinations.',
                 ]);

        // Assert that the Pollinations AI API was hit
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'text.pollinations.ai')
                && str_contains($request->body(), 'Test Uni')
                && str_contains($request->body(), 'Which university has Computer?');
        });
    }


    public function test_chat_returns_reply_if_gemini_api_succeeds(): void
    {
        // Mock the environment variable
        putenv('GEMINI_API_KEY=test_key_here');

        // Create a mock institution
        Institution::create([
            'nku' => 'Test Uni',
            'type' => 'university',
            'city' => 'Slemani',
            'tuition_plans' => [
                ['dept' => 'Computer', 'fee' => '1000$']
            ],
            'approved' => true,
        ]);

        // Fake the Google Gemini API call
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'This is the mock reply from Gemini.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/ai/chat', [
            'message' => 'Which university has Computer?',
            'history' => [
                ['role' => 'user', 'message' => 'Hi'],
                ['role' => 'model', 'message' => 'Hello, I am your advisor.'],
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'reply' => 'This is the mock reply from Gemini.',
                 ]);

        // Assert that the Gemini API was hit
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'generativelanguage.googleapis.com')
                && str_contains($request->body(), 'Test Uni')
                && str_contains($request->body(), 'Which university has Computer?');
        });
    }
}
