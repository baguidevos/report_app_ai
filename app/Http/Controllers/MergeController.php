<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ReportMergeService;
use App\Http\Requests\MergeReportsRequest;
use Illuminate\Http\Request;

class MergeController extends Controller
{
    protected $mergeService;

    public function __construct(ReportMergeService $mergeService)
    {
        $this->mergeService = $mergeService;
    }

    /**
     * Merge selected reports into a master report.
     */
    public function store(MergeReportsRequest $request)
    {
        try {
            $masterReport = $this->mergeService->merge(
                $request->report_ids,
                $request->frequency,
                auth()->id()
            );

            return redirect()->route('reports.edit', $masterReport)
                ->with('success', 'Rapports fusionnés avec succès!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la fusion: ' . $e->getMessage());
        }
    }
}
