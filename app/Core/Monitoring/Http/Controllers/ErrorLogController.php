<?php

namespace App\Core\Monitoring\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Monitoring\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ErrorLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ErrorLog::query();

        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('message', 'LIKE', "%{$search}%")
                  ->orWhere('url', 'LIKE', "%{$search}%");
            });
        }

        if ($from = $request->input('from')) {
            $query->where('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->where('created_at', '<=', $to);
        }

        if ($request->input('has_user')) {
            $query->whereNotNull('user_id');
        }

        $errors = $query->latest()->paginate(25);

        $stats = [
            'total' => ErrorLog::count(),
            'today' => ErrorLog::whereDate('created_at', today())->count(),
            'by_level' => ErrorLog::select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->pluck('count', 'level')
                ->toArray(),
        ];

        if ($request->expectsJson()) {
            return response()->json(['data' => $errors, 'stats' => $stats]);
        }

        return view('pages.monitoring.errors.index', compact('errors', 'stats'));
    }

    public function show(Request $request, ErrorLog $errorLog)
    {
        if ($request->expectsJson()) {
            return response()->json(['data' => $errorLog]);
        }

        return view('pages.monitoring.errors.show', ['error' => $errorLog]);
    }

    public function destroy(Request $request, ErrorLog $errorLog)
    {
        $errorLog->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Error log deleted.');
    }

    public function clearAll(Request $request)
    {
        ErrorLog::truncate();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All error logs cleared.');
    }
}
