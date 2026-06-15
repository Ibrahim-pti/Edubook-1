<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionRequest;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = InstitutionRequest::query();
        if ($request->status) $query->where('status', $request->status);
        $requests = $query->latest()->paginate(25)->withQueryString();
        return view('admin.requests.institutions', compact('requests'));
    }

    public function approve(InstitutionRequest $institutionRequest)
    {
        $institution = Institution::create([
            'nku'      => $institutionRequest->name,
            'phone'    => $institutionRequest->phone,
            'user_id'  => $institutionRequest->user_id,
            'approved' => true,
            'type'     => 'school',
        ]);
        $institutionRequest->update(['status' => 'approved']);
        return redirect()->route('admin.institutions.edit', $institution)
            ->with('success', 'داواکارییەکە پەسەند کرا و خوێندنگایەک دروستکرا.');
    }

    public function reject(InstitutionRequest $institutionRequest)
    {
        $institutionRequest->update(['status' => 'rejected']);
        return back()->with('success', 'داواکارییەکە ڕەتکرایەوە.');
    }

    public function destroy(InstitutionRequest $institutionRequest)
    {
        $institutionRequest->delete();
        return back()->with('success', 'داواکارییەکە سڕایەوە.');
    }
}
