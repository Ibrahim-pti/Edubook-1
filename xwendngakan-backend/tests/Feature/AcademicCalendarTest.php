<?php

namespace Tests\Feature;

use App\Models\AcademicEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicCalendarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

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

        $events = collect($response->json('data'));
        $testEvent = $events->firstWhere('title', 'Test Event');
        $this->assertNotNull($testEvent);
        $this->assertEquals('Test Description', $testEvent['description']);
    }

    public function test_admin_can_view_academic_calendar_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $event = AcademicEvent::create([
            'title' => 'Admin Test Event',
            'description' => 'Admin Test Description',
            'date' => '2026-06-18',
            'duration_days' => 2,
            'category' => 'holiday',
            'icon' => 'star',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.academic-calendar.index'));

        $response->assertStatus(200)
                 ->assertViewIs('admin.academic_calendar.index')
                 ->assertSee('Admin Test Event');
    }

    public function test_admin_can_view_create_form(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('admin.academic-calendar.create'));

        $response->assertStatus(200)
                 ->assertViewIs('admin.academic_calendar.form');
    }

    public function test_admin_can_store_academic_calendar_event(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.academic-calendar.store'), [
            'title' => 'New Event Created',
            'description' => 'New Description Created',
            'date' => '2026-07-04',
            'duration_days' => 3,
            'category' => 'exam',
            'icon' => 'school_rounded',
        ]);

        $response->assertRedirect(route('admin.academic-calendar.index'));
        $this->assertDatabaseHas('academic_events', [
            'title' => 'New Event Created',
            'category' => 'exam',
        ]);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $event = AcademicEvent::create([
            'title' => 'Event To Edit',
            'description' => 'Description To Edit',
            'date' => '2026-06-18',
            'duration_days' => 1,
            'category' => 'holiday',
            'icon' => 'star',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.academic-calendar.edit', $event->id));

        $response->assertStatus(200)
                 ->assertViewIs('admin.academic_calendar.form')
                 ->assertViewHas('event');
    }

    public function test_admin_can_update_academic_calendar_event(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $event = AcademicEvent::create([
            'title' => 'Original Event',
            'description' => 'Original Description',
            'date' => '2026-06-18',
            'duration_days' => 1,
            'category' => 'holiday',
            'icon' => 'star',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.academic-calendar.update', $event->id), [
            'title' => 'Updated Event Title',
            'description' => 'Updated Description',
            'date' => '2026-06-19',
            'duration_days' => 5,
            'category' => 'deadline',
            'icon' => 'assignment_rounded',
        ]);

        $response->assertRedirect(route('admin.academic-calendar.index'));
        $this->assertDatabaseHas('academic_events', [
            'id' => $event->id,
            'title' => 'Updated Event Title',
            'category' => 'deadline',
            'duration_days' => 5,
        ]);
    }

    public function test_admin_can_delete_academic_calendar_event(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $event = AcademicEvent::create([
            'title' => 'Event To Delete',
            'description' => 'Description To Delete',
            'date' => '2026-06-18',
            'duration_days' => 1,
            'category' => 'holiday',
            'icon' => 'star',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.academic-calendar.destroy', $event->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('academic_events', [
            'id' => $event->id,
        ]);
    }

    public function test_guest_cannot_access_academic_calendar_admin_panel(): void
    {
        $response = $this->get(route('admin.academic-calendar.index'));
        $response->assertRedirect(route('admin.login'));

        $responsePost = $this->post(route('admin.academic-calendar.store'), [
            'title' => 'Unauthorized Event',
            'date' => '2026-06-18',
            'duration_days' => 1,
            'category' => 'holiday',
        ]);
        $responsePost->assertRedirect(route('admin.login'));
    }
}

