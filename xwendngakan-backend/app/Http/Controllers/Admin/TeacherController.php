<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($search = $request->search) {
            $query->where(fn($q) => $q->where('name','like',"%$search%")
                                       ->orWhere('subject','like',"%$search%")
                                       ->orWhere('phone','like',"%$search%")
                                       ->orWhere('city','like',"%$search%"));
        }
        if ($request->type) $query->where('type', $request->type);
        if ($request->approved !== null && $request->approved !== '') {
            $query->where('is_approved', (bool)$request->approved);
        }

        $teachers = $query->latest()->paginate(25)->withQueryString();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'subject'          => 'nullable|string|max:255',
            'type'             => 'required|in:university,school',
            'city'             => 'required|string|max:100',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'hourly_rate'      => 'nullable|numeric|min:0',
            'about'            => 'nullable|string|max:2000',
            'video_url'        => 'nullable|url|max:255',
            'is_approved'      => 'boolean',
            'photo'            => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('teachers', 'public');
            $data['photo'] = '/storage/'.$path;
        }

        $data['is_approved'] = $request->boolean('is_approved');
        $teacher->update($data);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'زانیارییەکانی مامۆستاکە نوێکرایەوە.');
    }

    public function toggleApproval(Teacher $teacher)
    {
        $teacher->update(['is_approved' => !$teacher->is_approved]);
        $msg = $teacher->is_approved ? 'مامۆستاکە پەسەندکرا.' : 'پەسەندکردن لابرا.';
        return back()->with('success', $msg);
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return back()->with('success', 'مامۆستاکە سڕایەوە.');
    }
}
