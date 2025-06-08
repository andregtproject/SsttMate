<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute untuk Tes Sesi
|--------------------------------------------------------------------------
*/
// Rute ini akan MENULIS data ke sesi dan langsung mengarahkan Anda
Route::get('/test-tulis-sesi', function () {
    // Simpan data sederhana ke dalam sesi
    session(['kunci_tes' => 'Nilai ini HARUS tersimpan']);
    
    // Arahkan ke rute pembacaan
    return redirect('/test-baca-sesi');
});

// Rute ini akan MEMBACA data dari sesi
Route::get('/test-baca-sesi', function () {
    // Tampilkan semua data yang ada di dalam sesi saat ini
    dd(session()->all());
});


Route::get('/', function () {
    // Cek sesi Firebase, bukan Auth::check()
    if (session()->has('firebase_uid')) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// HAPUS 'verified' DARI MIDDLEWARE DI BAWAH INI
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['firebase.auth'])->name('dashboard');

Route::middleware('firebase.auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
