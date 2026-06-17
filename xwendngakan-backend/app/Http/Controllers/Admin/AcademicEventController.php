<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicEvent;
use Illuminate\Http\Request;

class AcademicEventController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicEvent::query();
        if ($search = $request->search) {
            $query->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        $events = $query->orderBy('date', 'asc')->paginate(25)->withQueryString();
        return view('admin.academic_calendar.index', compact('events'));
    }

    public function create()
    {
        return view('admin.academic_calendar.form', ['event' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'date'          => 'required|date',
            'duration_days' => 'required|integer|min:1',
            'category'      => 'required|string|in:holiday,exam,deadline',
            'icon'          => 'nullable|string|max:100',
        ]);
        AcademicEvent::create($data);
        return redirect()->route('admin.academic-calendar.index')->with('success', 'ڕووداوەکە زیادکرا.');
    }

    public function edit($id)
    {
        $event = AcademicEvent::findOrFail($id);
        return view('admin.academic_calendar.form', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = AcademicEvent::findOrFail($id);
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'date'          => 'required|date',
            'duration_days' => 'required|integer|min:1',
            'category'      => 'required|string|in:holiday,exam,deadline',
            'icon'          => 'nullable|string|max:100',
        ]);
        $event->update($data);
        return redirect()->route('admin.academic-calendar.index')->with('success', 'ڕووداوەکە نوێکرایەوە.');
    }

    public function destroy($id)
    {
        $event = AcademicEvent::findOrFail($id);
        $event->delete();
        return back()->with('success', 'ڕووداوەکە سڕایەوە.');
    }
}
