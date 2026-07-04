<?php

use App\Ai\Agents\ReportSummarizer;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MergeController;
use App\Http\Controllers\AgentController;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Ai\Enums\Lab;

// Landing page - redirect authenticated users to dashboard
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Dashboard with stats
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    $totalReports = $user->reports()->count();
    $dailyReports = $user->reports()->where('frequency', 'daily')->count();
    $weeklyReports = $user->reports()->where('frequency', 'weekly')->count();
    $monthlyReports = $user->reports()->where('frequency', 'monthly')->count();
    $recentReports = $user->reports()->latest()->take(6)->get();
    
    
    return view('dashboard', compact('totalReports', 'dailyReports', 'weeklyReports', 'monthlyReports', 'recentReports'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Reports CRUD
    Route::get('reports/merge', [ReportController::class, 'merge'])->name('reports.merge.form');
    
    Route::resource('reports', ReportController::class);

    // Merge reports
    Route::post('reports/merge', [MergeController::class, 'store'])->name('reports.merge');

    // Agent execution
    Route::post('agents/{agent}/execute', [AgentController::class, 'execute'])->name('agents.execute');
    Route::get('agents/job/{jobId}/status', [AgentController::class, 'status'])->name('agents.status');

    // AI Summary
    Route::post('reports/{report}/summarize', [\App\Http\Controllers\AiReportSummerizeController::class, 'summarize'])->name('reports.summarize');
});

require __DIR__.'/auth.php';
