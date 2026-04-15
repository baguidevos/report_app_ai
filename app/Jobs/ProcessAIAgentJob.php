<?php

namespace App\Jobs;

use App\Models\Agent;
use App\Models\Report;
use App\Services\AIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessAIAgentJob implements ShouldQueue
{
    use Queueable;

    public $agentId;
    public $reportId;
    public $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $agentId, int $reportId, int $userId)
    {
        $this->agentId = $agentId;
        $this->reportId = $reportId;
        $this->userId = $userId;
        $this->queue = 'ai-processing';
    }

    /**
     * Execute the job.
     */
    public function handle(AIService $aiService): void
    {
        $agent = Agent::findOrFail($this->agentId);
        $report = Report::find($this->reportId);

        if (!$agent || !$report) {
            Log::error('Agent or Report not found', [
                'agent_id' => $this->agentId,
                'report_id' => $this->reportId,
            ]);
            return;
        }

        // Store job status as processing
        Cache::put("agent_job_{$this->userId}_latest", [
            'status' => 'processing',
            'message' => 'Traitement en cours...',
        ], 300); // 5 minutes TTL

        try {
            // Execute the agent on the report content
            $result = $aiService->executeAgent($agent, $report->content);

            // Create a new report with the AI-processed content
            $newReport = Report::create([
                'user_id' => $this->userId,
                'title' => "[{$agent->name}] {$report->title}",
                'content' => $result,
                'frequency' => $report->frequency,
                'parent_report_id' => null,
            ]);

            // Store job status as completed
            Cache::put("agent_job_{$this->userId}_latest", [
                'status' => 'completed',
                'message' => 'Traitement terminé avec succès!',
                'report_id' => $newReport->id,
            ], 300);

        } catch (\Exception $e) {
            Log::error('AI Agent job failed', [
                'error' => $e->getMessage(),
                'agent_id' => $this->agentId,
                'report_id' => $this->reportId,
            ]);

            // Store job status as failed
            Cache::put("agent_job_{$this->userId}_latest", [
                'status' => 'failed',
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 300);
        }
    }
}
