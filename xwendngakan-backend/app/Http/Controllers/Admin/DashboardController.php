<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Post;
use App\Models\Cv;
use App\Models\InstitutionType;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_institutions'   => Institution::count(),
            'approved_institutions'=> Institution::where('approved', true)->count(),
            'pending_institutions' => Institution::where('approved', false)->count(),
            'total_users'          => User::count(),
            'total_teachers'       => Teacher::count(),
            'pending_teachers'     => Teacher::where('is_approved', false)->count(),
            'total_posts'          => Post::count(),
            'total_cvs'            => Cv::count(),
            'pending_cvs'          => Cv::where('is_reviewed', false)->count(),
            'cities'               => Institution::where('approved', true)->distinct('city')->count('city'),
            'gov'                  => Institution::where('approved', true)->where('type', 'gov')->count(),
            'priv'                 => Institution::where('approved', true)->where('type', 'priv')->count(),
            'school'               => Institution::where('approved', true)->where('type', 'school')->count(),
            'inst'                 => Institution::where('approved', true)->whereIn('type', ['inst5','inst2'])->count(),
            'kg'                   => Institution::where('approved', true)->where('type', 'kg')->count(),
            'dc'                   => Institution::where('approved', true)->where('type', 'dc')->count(),
        ];

        $pending = Institution::where('approved', false)->latest()->limit(10)->get();

        // Chart data: institutions by type (approved)
        $chartTypes = ['gov','priv','inst5','inst2','school','kg','dc'];
        $chartLabels = ['حکومی','ئەهلی','پەیمانگا ٥','پەیمانگا ٢','قوتابخانە','باخچە','سەنتەر'];
        $chartData = [];
        foreach ($chartTypes as $t) {
            $chartData[] = Institution::where('approved', true)->where('type', $t)->count();
        }

        return view('admin.dashboard', compact('stats', 'pending', 'chartLabels', 'chartData'));
    }
}
