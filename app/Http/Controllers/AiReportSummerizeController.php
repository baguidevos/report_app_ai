<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ReportSummarizer;
use App\Models\AiReportSummerize;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Ai\Enums\Lab;

class AiReportSummerizeController extends Controller
{
    /**
     * Generate or retrieve a summary for the given report.
     */
    public function summarize(Request $request, Report $report)
    {
        // Security check
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        // Cache key based on report ID and last update
        $cacheKey = "report_summary_{$report->id}_{$report->updated_at->timestamp}";

        try {
            $summaryData = Cache::remember($cacheKey, now()->addDays(7), function () use ($report) {
                // Call the AI Agent
                $agent = new ReportSummarizer();
                
                // We use openrouter/free as seen in web.php test
                $result = $agent->prompt($report->content, provider: Lab::Groq, model: 'openai/gpt-oss-120b');

                // Get the data from text (OpenRouter returns JSON in text)
                $data = json_decode($result->text, true);

                // Fallback to structured property if exists and text is not valid JSON
                if (empty($data) && isset($result->structured)) {
                    $data = $result->structured;
                }

                if (empty($data)) {
                    throw new \Exception("L'agent n'a pas retourné de données structurées valides.");
                }

                // Persist in database
                return AiReportSummerize::create([
                    'user_id' => Auth::id(),
                    'report_id' => $report->id,
                    'title' => $data['title'] ?? 'Résumé sans titre',
                    'body' => $data['body'] ?? '',
                    'type' => $data['type'] ?? $report->frequency,
                    'date_start' => $data['date_start'] ?? now()->toDateString(),
                    'date_end' => $data['date_end'] ?? now()->toDateString(),
                ]);
            });

            return redirect()->route('reports.edit', $report)->with('success', 'Résumé généré avec succès !');

        } catch (\Exception $e) {
            return redirect()->route('reports.edit', $report)->with('error', 'Erreur lors de la génération du résumé : ' . $e->getMessage());
        }
    }
}
