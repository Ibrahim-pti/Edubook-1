<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Institution;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('institution');

        if ($search = $request->search) {
            $query->where('title','like',"%$search%");
        }
        if ($request->approved !== null && $request->approved !== '') {
            $query->where('approved', (bool)$request->approved);
        }

        $posts = $query->latest()->paginate(25)->withQueryString();
        return view('admin.posts.index', compact('posts'));
    }

    public function toggleApproval(Post $post)
    {
        $post->update(['approved' => !$post->approved]);
        $msg = $post->approved ? 'پۆستەکە پەسەندکرا.' : 'پۆستەکە ڕەتکرایەوە.';
        return back()->with('success', $msg);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'پۆستەکە سڕایەوە.');
    }
}
