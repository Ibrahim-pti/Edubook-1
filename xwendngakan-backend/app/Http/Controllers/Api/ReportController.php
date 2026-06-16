<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Submit a report for an institution.
     */
    public function store(Request $request, int $institutionId)
    {
        $request->validate([
            'type'        => 'required|string|in:incorrect_info,closed,duplicate,spam,other',
            'description' => 'nullable|string|max:1000',
        ]);

        Institution::findOrFail($institutionId);

        $report = Report::create([
            'institution_id' => $institutionId,
            'user_id'        => $request->user()?->id,
            'type'           => $request->type,
            'description'    => $request->description,
            'status'         => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'سوپاس بۆ ڕاپۆرتەکەت. بەم زووانە پشکنین دەکرێت.',
            'data'    => $report,
        ], 201);
    }

    /**
     * Get report types for dropdown.
     */
    public function types()
    {
        return response()->json([
            'success' => true,
            'data'    => [
                ['key' => 'incorrect_info', 'name' => 'زانیاری هەڵە', 'name_en' => 'Incorrect Information', 'name_ar' => 'معلومات خاطئة'],
                ['key' => 'closed', 'name' => 'داخراوە', 'name_en' => 'Closed/No longer exists', 'name_ar' => 'مغلق'],
                ['key' => 'duplicate', 'name' => 'دووبارەیە', 'name_en' => 'Duplicate', 'name_ar' => 'مكرر'],
                ['key' => 'spam', 'name' => 'سپام', 'name_en' => 'Spam', 'name_ar' => 'بريد غير مرغوب'],
                ['key' => 'other', 'name' => 'هۆکاری تر', 'name_en' => 'Other', 'name_ar' => 'أخرى'],
            ],
        ]);
    }

    /**
     * Admin: Get all reports.
     */
    public function adminIndex(Request $request)
    {
        $query = Report::with('institution')->latest();

        $status = $request->get('status', 'pending');
        if (in_array($status, ['pending', 'reviewed', 'dismissed'])) {
            $query->where('status', $status);
        }
        // 'all' returns everything

        $perPage   = min((int) $request->get('per_page', 50), 200);
        $paginated = $query->paginate($perPage);

        $reports = $paginated->getCollection()->map(function ($report) {
            return [
                'id'             => $report->id,
                'institution_id' => $report->institution_id,
                'institution'    => $report->institution ? [
                    'id'   => $report->institution->id,
                    'nku'  => $report->institution->nku,
                    'nen'  => $report->institution->nen,
                    'nar'  => $report->institution->nar,
                    'type' => $report->institution->type,
                ] : null,
                'user_id'        => $report->user_id,
                'type'           => $report->type,
                'description'    => $report->description,
                'status'         => $report->status,
                'created_at'     => $report->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $reports,
            'meta'    => [
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'total'        => $paginated->total(),
                'has_more'     => $paginated->hasMorePages(),
                'pending'      => Report::where('status', 'pending')->count(),
                'reviewed'     => Report::where('status', 'reviewed')->count(),
                'dismissed'    => Report::where('status', 'dismissed')->count(),
            ],
        ]);
    }

    /**
     * Admin: Update report status (reviewed / dismissed).
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:reviewed,dismissed,pending',
        ]);

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();

        return response()->json([
            'success' => true,
            'data'    => $report,
            'message' => 'ستاتوسی ڕاپۆرتەکە نوێکرایەوە.',
        ]);
    }

    /**
     * Admin: Delete a report.
     */
    public function adminDestroy(int $id)
    {
        Report::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'ڕاپۆرتەکە سڕایەوە.',
        ]);
    }
}
