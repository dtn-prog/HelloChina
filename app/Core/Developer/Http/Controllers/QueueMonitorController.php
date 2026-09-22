<?php

namespace App\Core\Developer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class QueueMonitorController extends Controller
{
    public function index(Request $request)
    {
        $jobs = DB::table('jobs')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $failedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->paginate(25);

        $stats = [
            'pending' => DB::table('jobs')->count(),
            'failed' => DB::table('failed_jobs')->count(),
            'recent_completed' => $this->getRecentCompletedJobs(),
        ];

        if ($request->expectsJson()) {
            return response()->json(['data' => compact('jobs', 'failedJobs', 'stats')]);
        }

        return view('pages.developer.queue.index', compact('jobs', 'failedJobs', 'stats'));
    }

    public function retryJob(Request $request, string $jobId)
    {
        $failedJob = DB::table('failed_jobs')->where('id', $jobId)->first();

        if (!$failedJob) {
            return back()->with('error', 'Job not found.');
        }

        Queue::push(unserialize($failedJob->payload)['command']);
        DB::table('failed_jobs')->where('id', $jobId)->delete();

        return back()->with('success', 'Job retried successfully.');
    }

    public function deleteJob(Request $request, string $jobId)
    {
        DB::table('failed_jobs')->where('id', $jobId)->delete();

        return back()->with('success', 'Job deleted.');
    }

    public function retryAll(Request $request)
    {
        $failedJobs = DB::table('failed_jobs')->get();

        foreach ($failedJobs as $job) {
            Queue::push(unserialize($job->payload)['command']);
        }

        DB::table('failed_jobs')->truncate();

        return back()->with('success', 'All failed jobs retried.');
    }

    public function clearAll(Request $request)
    {
        DB::table('failed_jobs')->truncate();

        return back()->with('success', 'All failed jobs cleared.');
    }

    protected function getRecentCompletedJobs(): int
    {
        // This is a simplified version
        // In production, you might use a separate table for completed jobs
        return 0;
    }
}
