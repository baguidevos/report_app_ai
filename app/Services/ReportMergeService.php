<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportMergeService
{
    /**
     * Merge multiple reports into a master report.
     *
     * @param array $reportIds Array of report IDs to merge
     * @param string $masterFrequency Frequency for the master report
     * @param int $userId User ID creating the master report
     * @return Report The created master report
     */
    public function merge(array $reportIds, string $masterFrequency, int $userId): Report
    {
        // Fetch all reports with ordering preserved
        $reports = Report::whereIn('id', $reportIds)
            ->where('user_id', $userId)
            ->orderBy('id', 'asc')
            ->get();

        if ($reports->count() < 2) {
            throw new \InvalidArgumentException("At least 2 reports are required for merging.");
        }

        // Generate merged content
        $mergedContent = $this->generateMergedContent($reports);
        
        // Generate title based on date range
        $title = $this->generateMasterTitle($reports, $masterFrequency);

        // Create master report in a transaction
        return DB::transaction(function () use ($title, $mergedContent, $masterFrequency, $userId, $reports) {
            // Create the master report
            $masterReport = Report::create([
                'user_id' => $userId,
                'title' => $title,
                'content' => $mergedContent,
                'frequency' => $masterFrequency,
                'parent_report_id' => null,
            ]);

            // Update child reports to reference the master
            $reports->each(function ($report) use ($masterReport) {
                $report->update(['parent_report_id' => $masterReport->id]);
            });

            return $masterReport;
        });
    }

    /**
     * Generate merged content from multiple reports.
     *
     * @param \Illuminate\Database\Eloquent\Collection $reports
     * @return string
     */
    private function generateMergedContent($reports): string
    {
        $sections = [];

        foreach ($reports as $index => $report) {
            $section = [];
            
            // Add header for each report
            $section[] = "## {$report->title}";
            $section[] = "**Date:** {$report->created_at->format('d/m/Y H:i')}";
            $section[] = "**Fréquence:** " . $this->getFrequencyLabel($report->frequency);
            $section[] = ""; // Empty line for spacing
            
            // Add content
            $section[] = $report->content;
            
            // Add separator between reports (except after the last one)
            if ($index < $reports->count() - 1) {
                $section[] = "";
                $section[] = "---";
                $section[] = "";
            }
            
            $sections[] = implode("\n", $section);
        }

        return implode("\n\n", $sections);
    }

    /**
     * Generate a title for the master report.
     *
     * @param \Illuminate\Database\Eloquent\Collection $reports
     * @param string $frequency
     * @return string
     */
    private function generateMasterTitle($reports, string $frequency): string
    {
        $firstReport = $reports->first();
        $lastReport = $reports->last();
        
        $frequencyLabel = $this->getFrequencyLabel($frequency);
        $startDate = $firstReport->created_at->format('d/m/Y');
        $endDate = $lastReport->created_at->format('d/m/Y');
        
        return "Rapport {$frequencyLabel} - {$startDate} au {$endDate}";
    }

    /**
     * Get French label for frequency.
     *
     * @param string $frequency
     * @return string
     */
    private function getFrequencyLabel(string $frequency): string
    {
        return match($frequency) {
            'daily' => 'Quotidien',
            'weekly' => 'Hebdomadaire',
            'monthly' => 'Mensuel',
            default => $frequency,
        };
    }
}
