<?php

namespace Tests\Feature;

use App\Models\AcademicEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_academic_calendar_api(): void
    {
        AcademicEvent::create([
            'title' => 'Test Event',
            'description' => 'Test Description',
            'date' => '2026-06-18',
            'duration_days' => 1,
            'category' => 'holiday',
            'icon' => 'star',
        ]);

        $response = $this->getJson('/api/academic-calendar');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'title',
                             'description',
                             'date',
                             'duration_days',
                             'category',
                             'icon',
                         ]
                     ]
                 ]);

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Test Event', $response->json('data.0.title'));
    }
}
