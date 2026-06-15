<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionType;
use Illuminate\Http\Request;

class InstitutionTypeController extends Controller
{
    public function index()
    {
        $types = InstitutionType::orderBy('sort_order')->paginate(50);
        return view('admin.institution-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'name_en'         => 'nullable|string|max:255',
            'name_ar'         => 'nullable|string|max:255',
            'key'             => 'required|string|max:50|unique:institution_types,key',
            'emoji'           => 'nullable|string|max:10',
            'has_colleges'    => 'boolean',
            'has_departments' => 'boolean',
            'is_active'       => 'boolean',
            'sort_order'      => 'nullable|integer',
        ]);
        $data['has_colleges']    = $request->boolean('has_colleges');
        $data['has_departments'] = $request->boolean('has_departments');
        $data['is_active']       = $request->boolean('is_active');
        InstitutionType::create($data);
        return back()->with('success', 'جۆرەکە زیادکرا.');
    }

    public function update(Request $request, InstitutionType $institutionType)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'name_en'         => 'nullable|string|max:255',
            'name_ar'         => 'nullable|string|max:255',
            'key'             => 'required|string|max:50|unique:institution_types,key,'.$institutionType->id,
            'emoji'           => 'nullable|string|max:10',
            'has_colleges'    => 'boolean',
            'has_departments' => 'boolean',
            'is_active'       => 'boolean',
            'sort_order'      => 'nullable|integer',
        ]);
        $data['has_colleges']    = $request->boolean('has_colleges');
        $data['has_departments'] = $request->boolean('has_departments');
        $data['is_active']       = $request->boolean('is_active');
        $institutionType->update($data);
        return back()->with('success', 'جۆرەکە نوێکرایەوە.');
    }

    public function destroy(InstitutionType $institutionType)
    {
        $institutionType->delete();
        return back()->with('success', 'جۆرەکە سڕایەوە.');
    }
}
