<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;

// Landing page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Dashboard with monitoring state
Route::get('/dashboard', function (Request $request) {
    // Ambil status monitoring dari session (default: false)
    $isMonitoring = session('isMonitoring', false);

    // Data dummy untuk monitoring aktif
    $monitorData = [
        'db' => 69,
        'account' => 'H. Kikir',
        'mic_status' => 'Online',
        'timer' => '00 h : 01 m : 20 s',
        'min' => 20,
        'max' => 75,
        'avg' => 47.5,
        'safety' => 'Potential Risk',
        'safety_color' => 'orange-400',
        'safety_text' => 'Potential Risk',
        'mic_color' => 'green-500',
        'finish' => true,
    ];

    // Data dummy untuk monitoring nonaktif
    $emptyData = [
        'db' => '--',
        'account' => 'H. Kikir',
        'mic_status' => 'Offline',
        'timer' => '00 h : 00 m : 00 s',
        'min' => '00',
        'max' => '100',
        'avg' => '50',
        'safety' => 'None',
        'safety_color' => 'gray-400',
        'safety_text' => 'None',
        'mic_color' => 'red-500',
        'finish' => false,
    ];

    $data = $isMonitoring ? $monitorData : $emptyData;

    return view('dashboard', [
        'isMonitoring' => $isMonitoring,
        'data' => $data
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Start monitoring (set session)
Route::post('/monitoring/start', function () {
    session(['isMonitoring' => true]);
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('monitoring.start');

// Finish monitoring (unset session)
Route::post('/monitoring/finish', function () {
    session(['isMonitoring' => false]);
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('monitoring.finish');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';