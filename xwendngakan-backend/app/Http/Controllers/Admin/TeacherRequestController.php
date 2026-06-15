<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherRequest;
use Illuminate\Http\Request;

class TeacherRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherRequest::query();
        if ($request->status) $query->where('status', $request->status);
        $requests = $query->latest()->paginate(25)->withQueryString();
        return view('admin.requests.teachers', compact('requests'));
    }

    public function updateStatus(Request $request, TeacherRequest $teacherRequest)
    {
        $data = $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $teacherRequest->update($data);
        return back()->with('success', 'بارودۆخەکە نوێکرایەوە.');
    }

    public function destroy(TeacherRequest $teacherRequest)
    {
        $teacherRequest->delete();
        return back()->with('success', 'داواکارییەکە سڕایەوە.');
    }
}
