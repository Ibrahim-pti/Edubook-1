<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use Illuminate\Http\Request;

class CvController extends Controller
{
    public function index(Request $request)
    {
        $query = Cv::query();

        if ($search = $request->search) {
            $query->where(fn($q) => $q->where('name','like',"%$search%")
                                       ->orWhere('phone','like',"%$search%")
                                       ->orWhere('field','like',"%$search%")
                                       ->orWhere('city','like',"%$search%"));
        }
        if ($request->reviewed !== null && $request->reviewed !== '') {
            $query->where('is_reviewed', (bool)$request->reviewed);
        }
        if ($request->city) $query->where('city', $request->city);

        $cvs   = $query->latest()->paginate(25)->withQueryString();
        $cities = Cv::distinct()->orderBy('city')->pluck('city');
        return view('admin.cvs.index', compact('cvs','cities'));
    }

    public function show(Cv $cv)
    {
        return view('admin.cvs.show', compact('cv'));
    }

    public function toggleReview(Cv $cv)
    {
        $cv->update(['is_reviewed' => !$cv->is_reviewed]);
        $msg = $cv->is_reviewed ? 'CV وەک پشکنینکراو نیشانکرا.' : 'CV وەک نەپشکنینکراو نیشانکرا.';
        return back()->with('success', $msg);
    }

    public function destroy(Cv $cv)
    {
        $cv->delete();
        return back()->with('success', 'CVەکە سڕایەوە.');
    }
}
