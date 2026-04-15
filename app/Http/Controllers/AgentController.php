<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Report;
use App\Jobs\ProcessAIAgentJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AgentController extends Controller
{
    /**
     * Execute an agent on a report.
     */
    public function execute(Request $request, Agent $agent)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
        ]);

        $report = Report::findOrFail($request->report_id);

        // Check authorization
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        // Dispatch the job
        ProcessAIAgentJob::dispatch($agent->id, $report->id, Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Traitement IA démarré. Le résultat sera disponible sous peu.',
        ]);
    }

    /**
     * Check the status of an AI agent job.
     */
    public function status($jobId)
    {
        // For simplicity, we're using cache to track the latest job status per user
        // In production, you'd use Laravel's job batching or a proper job tracking system
        $status = Cache::get("agent_job_" . Auth::id() . "_latest");

        if (!$status) {
            return response()->json([
                'status' => 'unknown',
                'message' => 'Aucun traitement en cours',
            ]);
        }

        return response()->json($status);
    }
}
