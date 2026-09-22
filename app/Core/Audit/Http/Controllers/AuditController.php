<?php

namespace App\Core\Audit\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Audit\Services\AuditService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject');

        if ($logName = $request->input('log_name')) {
            $query->where('log_name', $logName);
        }

        if ($event = $request->input('event')) {
            $query->where('event', $event);
        }

        if ($causerId = $request->input('causer_id')) {
            $query->where('causer_id', $causerId);
        }

        if ($subjectType = $request->input('subject_type')) {
            $query->where('subject_type', $subjectType);
        }

        if ($from = $request->input('from')) {
            $query->where('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->where('created_at', '<=', $to);
        }

        $activities = $query->latest()->paginate(25);

        if ($request->expectsJson()) {
            return response()->json(['data' => $activities]);
        }

        return view('pages.audit.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        $activity->load('causer', 'subject');

        if (request()->expectsJson()) {
            return response()->json(['data' => $activity]);
        }

        return view('pages.audit.show', compact('activity'));
    }

    public function userHistory(Request $request, int $userId)
    {
        $activities = $this->auditService->getActivitiesByUser($userId, 50);

        if ($request->expectsJson()) {
            return response()->json(['data' => $activities]);
        }

        return view('pages.audit.user-history', compact('activities'));
    }

    public function export(Request $request)
    {
        $query = Activity::with('causer', 'subject');

        if ($from = $request->input('from')) {
            $query->where('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->where('created_at', '<=', $to);
        }

        $activities = $query->latest()->get();

        $csv = "ID,Log Name,Event,Description,Subject Type,Subject ID,Causer,Causer IP,Time\n";
        foreach ($activities as $activity) {
            $csv .= implode(',', [
                $activity->id,
                $activity->log_name,
                $activity->event ?? '',
                '"' . str_replace('"', '""', $activity->description) . '"',
                $activity->subject_type ?? '',
                $activity->subject_id ?? '',
                $activity->causer?->name ?? 'System',
                $activity->ip_address ?? '',
                $activity->created_at->format('Y-m-d H:i:s'),
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="audit_logs_' . now()->format('Y-m-d_His') . '.csv"');
    }
}
