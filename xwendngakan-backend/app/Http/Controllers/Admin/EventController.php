<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();
        if ($search = $request->search) $query->where('title','like',"%$search%");
        $events = $query->latest()->paginate(25)->withQueryString();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.form', ['event' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'location'    => 'nullable|string|max:255',
            'organizer'   => 'nullable|string|max:255',
            'is_active'   => 'boolean',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events','public');
            $data['image'] = '/storage/'.$path;
        }
        $data['is_active'] = $request->boolean('is_active');
        Event::create($data);
        return redirect()->route('admin.events.index')->with('success','ڕووداوەکە زیادکرا.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'location'    => 'nullable|string|max:255',
            'organizer'   => 'nullable|string|max:255',
            'is_active'   => 'boolean',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events','public');
            $data['image'] = '/storage/'.$path;
        }
        $data['is_active'] = $request->boolean('is_active');
        $event->update($data);
        return redirect()->route('admin.events.index')->with('success','ڕووداوەکە نوێکرایەوە.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success','ڕووداوەکە سڕایەوە.');
    }
}
