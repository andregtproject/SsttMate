<?php

use Illuminate\Support\Facades\Route;
use SoundService\Services\FirebaseService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-firebase', function () {
    try {
        $firebaseService = new FirebaseService();
        $data = $firebaseService->getCurrentSoundLevel();

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil dari Firebase!',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Koneksi ke Firebase berhasil, tapi tidak ada data di path sound_level.'
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menghubungi Firebase.',
            'error_details' => $e->getMessage()
        ], 500);
    }
});