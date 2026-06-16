<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::query();

        if ($search = $request->search) {
            $query->where(fn($q) => $q->where('nku','like',"%$search%")
                                       ->orWhere('nen','like',"%$search%")
                                       ->orWhere('city','like',"%$search%")
                                       ->orWhere('phone','like',"%$search%"));
        }
        if ($request->type) $query->where('type', $request->type);
        if ($request->approved !== null && $request->approved !== '') {
            $query->where('approved', (bool)$request->approved);
        }
        if ($request->city) $query->where('city', $request->city);

        $institutions = $query->latest()->paginate(25)->withQueryString();
        $types        = InstitutionType::ordered()->get();
        $cities       = Institution::whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('admin.institutions.index', compact('institutions','types','cities'));
    }

    public function create()
    {
        $types  = InstitutionType::active()->ordered()->get();
        $cities = Institution::whereNotNull('city')->distinct()->orderBy('city')->pluck('city');
        return view('admin.institutions.create', compact('types', 'cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nku'            => 'required|string|max:255',
            'type'           => 'required|string|max:50',
            'country'        => 'nullable|string|max:100',
            'city'           => 'required|string|max:100',
            'addr'           => 'nullable|string|max:255',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'nen'            => 'nullable|string|max:255',
            'nar'            => 'nullable|string|max:255',
            'desc'           => 'nullable|string',
            'desc_en'        => 'nullable|string',
            'desc_ar'        => 'nullable|string',
            'desc_kbd'       => 'nullable|string',
            'founded_year'   => 'nullable|integer|min:1800|max:'.date('Y'),
            'students_count' => 'nullable|integer|min:0',
            'level'          => 'nullable|string|max:255',
            'fee'            => 'nullable|string|max:255',
            'meal'           => 'nullable|string|max:255',
            'uniform'        => 'nullable|string|max:255',
            'books'          => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'manager_name'   => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'web'            => 'nullable|url|max:255',
            'fb'             => 'nullable|string|max:255',
            'wa'             => 'nullable|string|max:255',
            'ig'             => 'nullable|string|max:255',
            'tg'             => 'nullable|string|max:255',
            'yt'             => 'nullable|string|max:255',
            'tk'             => 'nullable|string|max:255',
            'video'          => 'nullable|url|max:500',
            'user_id'        => 'nullable|integer|exists:users,id',
            'is_premium'     => 'nullable|boolean',
            'logo'           => 'nullable|file|mimes:jpg,jpeg,png,webp|max:20480',
            'cover'          => 'nullable|file|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = '/storage/'.$path;
        }

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['img'] = '/storage/'.$path;
        }

        unset($data['cover']);
        $data['approved']   = false;
        $data['is_premium'] = isset($data['is_premium']);
        Institution::create($data);

        return redirect()->route('admin.institutions.index')
            ->with('success', 'خوێندنگاکە بە سەرکەوتوویی زیادکرا.');
    }

    public function edit(Institution $institution)
    {
        $types  = InstitutionType::active()->ordered()->get();
        $cities = Institution::whereNotNull('city')->distinct()->orderBy('city')->pluck('city');
        return view('admin.institutions.edit', compact('institution', 'types', 'cities'));
    }

    public function update(Request $request, Institution $institution)
    {
        $data = $request->validate([
            'nku'            => 'required|string|max:255',
            'type'           => 'required|string|max:50',
            'country'        => 'nullable|string|max:100',
            'city'           => 'required|string|max:100',
            'addr'           => 'nullable|string|max:255',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'nen'            => 'nullable|string|max:255',
            'nar'            => 'nullable|string|max:255',
            'desc'           => 'nullable|string',
            'desc_en'        => 'nullable|string',
            'desc_ar'        => 'nullable|string',
            'desc_kbd'       => 'nullable|string',
            'founded_year'   => 'nullable|integer|min:1800|max:'.date('Y'),
            'students_count' => 'nullable|integer|min:0',
            'level'          => 'nullable|string|max:255',
            'fee'            => 'nullable|string|max:255',
            'meal'           => 'nullable|string|max:255',
            'uniform'        => 'nullable|string|max:255',
            'books'          => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'manager_name'   => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'web'            => 'nullable|url|max:255',
            'fb'             => 'nullable|string|max:255',
            'wa'             => 'nullable|string|max:255',
            'ig'             => 'nullable|string|max:255',
            'tg'             => 'nullable|string|max:255',
            'yt'             => 'nullable|string|max:255',
            'tk'             => 'nullable|string|max:255',
            'video'          => 'nullable|url|max:500',
            'user_id'        => 'nullable|integer|exists:users,id',
            'approved'       => 'nullable|boolean',
            'is_premium'     => 'nullable|boolean',
            'logo'           => 'nullable|file|mimes:jpg,jpeg,png,webp|max:20480',
            'cover'          => 'nullable|file|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = '/storage/'.$path;
        }

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['img'] = '/storage/'.$path;
        }

        // Checkboxes send nothing when unchecked — normalise to boolean
        $data['approved']   = $request->boolean('approved');
        $data['is_premium'] = $request->boolean('is_premium');

        unset($data['cover']);
        $institution->update($data);

        return redirect()->route('admin.institutions.index')
            ->with('success', 'زانیارییەکانی خوێندنگاکە نوێکرایەوە.');
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();
        return back()->with('success', 'خوێندنگاکە سڕایەوە.');
    }

    public function toggleApproval(Institution $institution)
    {
        $institution->update(['approved' => !$institution->approved]);
        $msg = $institution->approved ? 'خوێندنگاکە پەسەندکرا.' : 'خوێندنگاکە ڕەتکرایەوە.';
        return back()->with('success', $msg);
    }
}
