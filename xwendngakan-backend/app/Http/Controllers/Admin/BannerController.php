<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(25);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'url'         => 'nullable|url|max:255',
            'tag'         => 'nullable|string|max:40',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $data['image'] = '/storage/'.$path;
        }
        $data['title'] = 'Banner ' . time(); // Auto-generate title since it's removed from form
        $data['is_active'] = $request->boolean('is_active');
        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'بانەرەکە زیادکرا.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'url'         => 'nullable|url|max:255',
            'tag'         => 'nullable|string|max:40',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $data['image'] = '/storage/'.$path;
        }
        $data['is_active'] = $request->boolean('is_active');
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'بانەرەکە نوێکرایەوە.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'بانەرەکە سڕایەوە.');
    }
}
