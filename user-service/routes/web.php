<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\GuideController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/history', [HistoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('history');

Route::get('/guide', [GuideController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('guide');

Route::get('/api/sound-data', [DashboardController::class, 'getSoundDataApi'])
    ->middleware(['auth'])
    ->name('api.sound-data');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Monitoring routes
    Route::post('/monitoring/start', [DashboardController::class, 'startMonitoring'])->name('monitoring.start');
    Route::post('/monitoring/finish', [DashboardController::class, 'finishMonitoring'])->name('monitoring.finish');
});

require __DIR__.'/auth.php';