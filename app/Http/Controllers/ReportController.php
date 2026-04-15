<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Report::with(['parent', 'children'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by frequency if provided
        if ($request->filled('frequency')) {
            $query->byFrequency($request->frequency);
        }

        $reports = $query->paginate(15);

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Show the merge interface.
     */
    public function merge(Request $request)
    {
        $query = Report::where('user_id', Auth::id())
            ->whereNull('parent_report_id') // Only show reports that aren't already children
            ->orderBy('created_at', 'desc');

        // Filter by frequency if provided
        if ($request->filled('frequency')) {
            $query->byFrequency($request->frequency);
        }

        $reports = $query->get();

        return view('reports.merge', compact('reports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {

        $report = Report::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'frequency' => $request->input('frequency'),
        ]);

        return redirect()->route('reports.edit', $report)
            ->with('success', 'Rapport créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        // Check authorization
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        // Check authorization
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        // Check authorization
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        $report->update($request->validated());

        return redirect()->route('reports.edit', $report)
            ->with('success', 'Rapport mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {

        // Check authorization
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Rapport supprimé avec succès.');
    }
}
