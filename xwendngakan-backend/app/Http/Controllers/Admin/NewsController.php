<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();
        if ($search = $request->search) {
            $query->where('title', 'like', "%$search%");
        }
        $news = $query->latest()->paginate(25)->withQueryString();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.form', ['news' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'boolean',
            'image'     => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = '/storage/'.$path;
        }
        $data['is_active'] = $request->boolean('is_active');
        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'هەواڵەکە زیادکرا.');
    }

    public function edit(News $news)
    {
        return view('admin.news.form', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'boolean',
            'image'     => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = '/storage/'.$path;
        }
        $data['is_active'] = $request->boolean('is_active');
        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'هەواڵەکە نوێکرایەوە.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return back()->with('success', 'هەواڵەکە سڕایەوە.');
    }
}
