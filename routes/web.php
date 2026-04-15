<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MergeController;
use App\Http\Controllers\AgentController;
use Illuminate\Support\Facades\Route;

// Landing page - redirect authenticated users to reports
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('reports.index');
    }
    return view('welcome');
})->name('home');

// Dashboard redirects to reports for authenticated users
Route::get('/dashboard', function () {
    return redirect()->route('reports.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Reports CRUD
    Route::resource('reports', ReportController::class);
    Route::get('reports/merge', [ReportController::class, 'merge'])->name('reports.merge.form');

    // Merge reports
    Route::post('reports/merge', [MergeController::class, 'store'])->name('reports.merge');

    // Agent execution
    Route::post('agents/{agent}/execute', [AgentController::class, 'execute'])->name('agents.execute');
    Route::get('agents/job/{jobId}/status', [AgentController::class, 'status'])->name('agents.status');
});

require __DIR__.'/auth.php';
