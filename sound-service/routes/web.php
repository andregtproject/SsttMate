<?php

use Illuminate\Support\Facades\Route;
use SoundService\Services\FirebaseService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-firebase', function () {
    try {
        $firebaseService = new FirebaseService();
        
        // Test connection first
        $connectionTest = $firebaseService->testFirebaseConnection();
        error_log('Firebase connection test result: ' . json_encode($connectionTest));
        
        // Get current data
        $data = $firebaseService->getCurrentSoundLevel();
        error_log('Current sound level data: ' . json_encode($data));

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil dari Firebase!',
                'data' => $data,
                'connection_test' => $connectionTest,
                'timestamp' => time(),
                'formatted_time' => date('Y-m-d H:i:s')
            ]);
        } else {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Koneksi ke Firebase berhasil, tapi tidak ada data di path sound_level.',
                'connection_test' => $connectionTest,
                'timestamp' => time()
            ], 404);
        }
    } catch (\Exception $e) {
        error_log('Test Firebase error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menghubungi Firebase.',
            'error_details' => $e->getMessage(),
            'timestamp' => time()
        ], 500);
    }
});

// Add debug endpoint to force fresh data
Route::get('/debug-firebase', function () {
    try {
        $firebaseService = new FirebaseService();
        
        // Force multiple calls to see if data changes
        $calls = [];
        for ($i = 0; $i < 5; $i++) {
            $data = $firebaseService->getCurrentSoundLevel();
            $calls[] = [
                'call' => $i + 1,
                'data' => $data,
                'timestamp' => time()
            ];
            sleep(1); // Wait 1 second between calls
        }
        
        return response()->json([
            'status' => 'debug',
            'message' => 'Multiple Firebase calls completed',
            'calls' => $calls,
            'total_calls' => count($calls)
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Add simulation endpoint for testing
Route::get('/simulate-sound', function () {
    try {
        $firebaseService = new FirebaseService();
        $data = $firebaseService->getSimulatedSoundLevel();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Simulated sound data generated',
            'data' => $data,
            'timestamp' => time()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Add comparison endpoint
Route::get('/compare-data', function () {
    try {
        $firebaseService = new FirebaseService();
        
        $realData = $firebaseService->getCurrentSoundLevel();
        $simulatedData = $firebaseService->getSimulatedSoundLevel();
        
        return response()->json([
            'timestamp' => time(),
            'real_firebase_data' => $realData,
            'simulated_data' => $simulatedData,
            'comparison' => [
                'real_amplitude' => $realData['amplitude'] ?? 'N/A',
                'simulated_amplitude' => $simulatedData['amplitude'],
                'difference' => isset($realData['amplitude']) ? 
                    abs($realData['amplitude'] - $simulatedData['amplitude']) : 'N/A'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Add GraphQL endpoint that user-service expects
Route::any('/graphql.php', function () {
    try {
        $timestamp = time();
        error_log('GraphQL endpoint called at: ' . date('Y-m-d H:i:s', $timestamp));
        
        $firebaseService = new FirebaseService();
        
        // Selalu gunakan data dengan variasi (bukan data statis Firebase)
        $data = $firebaseService->getCurrentSoundLevel();
        
        // Pastikan selalu ada variasi
        if (!isset($data['simulation_info']) && !isset($data['simulation_details'])) {
            error_log('GraphQL: Using full simulation for dynamic data');
            $data = $firebaseService->getSimulatedSoundLevel();
        }
        
        error_log('GraphQL - Dynamic data: ' . json_encode($data));
        
        $responseData = [
            'data' => [
                'currentSoundLevel' => $data
            ],
            'timestamp' => $timestamp
        ];
        
        return response()->json($responseData);
        
    } catch (\Exception $e) {
        error_log('GraphQL endpoint error: ' . $e->getMessage());
        
        // Fallback ke simulasi penuh
        $firebaseService = new FirebaseService();
        $fallbackData = $firebaseService->getSimulatedSoundLevel();
        $fallbackData['data_source'] = 'error_fallback_simulation';
        
        return response()->json([
            'data' => [
                'currentSoundLevel' => $fallbackData
            ],
            'error_info' => $e->getMessage()
        ], 200);
    }
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'service' => 'sound-service']);
});