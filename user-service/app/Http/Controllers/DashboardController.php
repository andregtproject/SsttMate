<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Create SoundServiceClient instance
            $soundServiceClient = new \UserService\Services\SoundServiceClient();
            
            // Test connection to sound service
            $connectionTest = $soundServiceClient->testConnection();
            
            // Get current sound data
            $soundResponse = $soundServiceClient->getAllSoundData();
            $currentSoundLevel = $soundResponse['data'] ?? null;
            
            // Check if monitoring is active and calculate timer
            $isMonitoring = session('monitoring_active', false);
            $timer = '00:00:00';
            $monitoringData = session('monitoring_data', ['readings' => [], 'min' => null, 'max' => null]);
            
            if ($isMonitoring && session('monitoring_start_time')) {
                $startTime = \Carbon\Carbon::parse(session('monitoring_start_time'));
                $now = now();
                $elapsed = $now->diffInSeconds($startTime);
                
                $hours = intval($elapsed / 3600);
                $minutes = intval(($elapsed % 3600) / 60);
                $seconds = $elapsed % 60;
                $timer = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                
                // Store current reading if monitoring
                if ($currentSoundLevel && isset($currentSoundLevel['db'])) {
                    $dbValue = (float)$currentSoundLevel['db'];
                    $monitoringData['readings'][] = [
                        'value' => $dbValue,
                        'timestamp' => time()
                    ];
                    
                    // Keep only last 30 readings (1 minute at 2-second intervals)
                    if (count($monitoringData['readings']) > 30) {
                        $monitoringData['readings'] = array_slice($monitoringData['readings'], -30);
                    }
                    
                    // Update min/max
                    if ($monitoringData['min'] === null || $dbValue < $monitoringData['min']) {
                        $monitoringData['min'] = $dbValue;
                    }
                    if ($monitoringData['max'] === null || $dbValue > $monitoringData['max']) {
                        $monitoringData['max'] = $dbValue;
                    }
                    
                    session(['monitoring_data' => $monitoringData]);
                }
            }
            
            // Calculate average from stored readings
            $avg = 0;
            if (!empty($monitoringData['readings'])) {
                $sum = array_sum(array_column($monitoringData['readings'], 'value'));
                $avg = round($sum / count($monitoringData['readings']), 1);
            }
            
            // Prepare data structure that dashboard expects
            $data = [
                'sound_level' => $currentSoundLevel['db'] ?? 0,
                'account' => auth()->user()->name ?? 'User',
                'mic_status' => $connectionTest['status'] === 'success' ? 'Connected' : 'Disconnected',
                'mic_color' => $connectionTest['status'] === 'success' ? 'green-500' : 'red-500',
                'timer' => $timer,
                'min' => $monitoringData['min'] ?? 0,
                'max' => $monitoringData['max'] ?? 0,
                'avg' => $avg,
                'safety_color' => $this->getSafetyColor($currentSoundLevel['db'] ?? 0),
                'safety_text' => $this->getSafetyText($currentSoundLevel['db'] ?? 0),
                'chart_data' => $this->getChartData($monitoringData['readings'] ?? []),
            ];
            
            return view('dashboard', [
                'data' => $data,
                'soundData' => $currentSoundLevel,
                'soundResponse' => $soundResponse,
                'connectionStatus' => $connectionTest['status'] ?? 'unknown',
                'connectionMessage' => $connectionTest['message'] ?? 'No connection info',
                'isMonitoring' => $isMonitoring
            ]);
            
        } catch (\Exception $e) {
            // Provide default data when service is unavailable
            $data = [
                'sound_level' => 0,
                'account' => auth()->user()->name ?? 'User',
                'mic_status' => 'Disconnected',
                'mic_color' => 'red-500',
                'timer' => '00:00:00',
                'min' => 0,
                'max' => 0,
                'avg' => 0,
                'safety_color' => 'gray-500',
                'safety_text' => 'No Data',
                'chart_data' => [],
            ];
            
            return view('dashboard', [
                'data' => $data,
                'soundData' => null,
                'soundResponse' => ['status' => 'error', 'message' => $e->getMessage(), 'data' => null],
                'connectionStatus' => 'error',
                'connectionMessage' => 'Failed to connect to sound service: ' . $e->getMessage(),
                'isMonitoring' => false
            ]);
        }
    }

    public function getSoundDataApi()
    {
        try {
            // Add detailed logging for debugging
            error_log('DashboardController::getSoundDataApi() called at ' . now());
            
            $soundServiceClient = new \UserService\Services\SoundServiceClient();
            
            // Test connection first
            $connectionTest = $soundServiceClient->testConnection();
            error_log('Connection test result: ' . json_encode($connectionTest));
            
            // Get sound data
            $soundResponse = $soundServiceClient->getAllSoundData();
            error_log('Sound response: ' . json_encode($soundResponse));
            
            $currentSoundLevel = $soundResponse['data'] ?? null;
            
            // Update monitoring data if active
            $isMonitoring = session('monitoring_active', false);
            $monitoringData = session('monitoring_data', ['readings' => [], 'min' => null, 'max' => null]);
            $timer = '00:00:00';
            
            if ($isMonitoring && session('monitoring_start_time')) {
                try {
                    $startTime = \Carbon\Carbon::parse(session('monitoring_start_time'));
                    $now = now();
                    $elapsed = $now->diffInSeconds($startTime);
                    
                    // Ensure elapsed is not negative
                    if ($elapsed < 0) {
                        error_log('Negative elapsed time detected, resetting monitoring start time');
                        session(['monitoring_start_time' => now()->toDateTimeString()]);
                        $elapsed = 0;
                    }
                    
                    $hours = intval($elapsed / 3600);
                    $minutes = intval(($elapsed % 3600) / 60);
                    $seconds = $elapsed % 60;
                    $timer = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                    
                    error_log("Timer calculation - Start: {$startTime}, Now: {$now}, Elapsed: {$elapsed}s, Timer: {$timer}");
                    
                } catch (\Exception $e) {
                    error_log('Timer calculation error: ' . $e->getMessage());
                    // Reset monitoring start time if there's an error
                    session(['monitoring_start_time' => now()->toDateTimeString()]);
                    $timer = '00:00:00';
                }
                
                if ($currentSoundLevel && isset($currentSoundLevel['db'])) {
                    $dbValue = (float)$currentSoundLevel['db'];
                    $monitoringData['readings'][] = [
                        'value' => $dbValue,
                        'timestamp' => time()
                    ];
                    
                    if (count($monitoringData['readings']) > 30) {
                        $monitoringData['readings'] = array_slice($monitoringData['readings'], -30);
                    }
                    
                    if ($monitoringData['min'] === null || $dbValue < $monitoringData['min']) {
                        $monitoringData['min'] = $dbValue;
                    }
                    if ($monitoringData['max'] === null || $dbValue > $monitoringData['max']) {
                        $monitoringData['max'] = $dbValue;
                    }
                    
                    session(['monitoring_data' => $monitoringData]);
                }
            }
            
            $avg = 0;
            if (!empty($monitoringData['readings'])) {
                $sum = array_sum(array_column($monitoringData['readings'], 'value'));
                $avg = round($sum / count($monitoringData['readings']), 1);
            }
            
            $apiResponse = [
                'success' => true,
                'data' => $currentSoundLevel,
                'response' => $soundResponse,
                'connection_test' => $connectionTest,
                'stats' => [
                    'min' => $monitoringData['min'] ?? 0,
                    'max' => $monitoringData['max'] ?? 0,
                    'avg' => $avg,
                    'timer' => $timer,
                    'mic_status' => $connectionTest['status'] === 'success' ? 'Connected' : 'Disconnected',
                    'mic_color' => $connectionTest['status'] === 'success' ? 'green-500' : 'red-500',
                    'safety_color' => $this->getSafetyColor($currentSoundLevel['db'] ?? 0),
                    'safety_text' => $this->getSafetyText($currentSoundLevel['db'] ?? 0),
                ],
                'chart_data' => $this->getChartData($monitoringData['readings'] ?? []),
                'timestamp' => time(),
                'debug_info' => [
                    'monitoring_active' => $isMonitoring,
                    'session_start_time' => session('monitoring_start_time'),
                    'current_time' => now()->toDateTimeString(),
                    'timer_calculated' => $timer,
                ]
            ];
            
            error_log('Final API response stats: ' . json_encode($apiResponse['stats']));
            
            return response()->json($apiResponse);
            
        } catch (\Exception $e) {
            error_log('DashboardController::getSoundDataApi() error: ' . $e->getMessage());
            error_log('Error trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => time(),
                'debug_info' => [
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    public function startMonitoring()
    {
        // Ensure we set a proper start time
        $startTime = now()->toDateTimeString();
        
        session([
            'monitoring_active' => true, 
            'monitoring_start_time' => $startTime,
            'monitoring_data' => ['readings' => [], 'min' => null, 'max' => null]
        ]);
        
        error_log('Monitoring started at: ' . $startTime);
        
        return redirect()->route('dashboard')->with('success', 'Monitoring started');
    }
    
    public function finishMonitoring()
    {
        session(['monitoring_active' => false]);
        session()->forget(['monitoring_start_time', 'monitoring_data']);
        return redirect()->route('dashboard')->with('success', 'Monitoring finished');
    }
    
    private function getChartData($readings)
    {
        $labels = [];
        $data = [];
        
        if (empty($readings)) {
            return ['labels' => [], 'data' => []];
        }
        
        $startTime = $readings[0]['timestamp'] ?? time();
        foreach ($readings as $index => $reading) {
            $secondsElapsed = ($reading['timestamp'] - $startTime);
            $labels[] = $secondsElapsed . 's';
            $data[] = $reading['value'];
        }
        
        return ['labels' => $labels, 'data' => $data];
    }
    
    private function getSafetyColor($amplitude)
    {
        if ($amplitude <= 50) return 'green-500';      // Low: 0-50
        if ($amplitude <= 70) return 'yellow-500';     // Normal: 51-70
        if ($amplitude <= 100) return 'orange-500';    // Moderate: 71-100
        return 'red-500';                              // High: 101+
    }
    
    private function getSafetyText($amplitude)
    {
        if ($amplitude <= 50) return 'Low';            // 0-50
        if ($amplitude <= 70) return 'Normal';         // 51-70
        if ($amplitude <= 100) return 'Moderate';      // 71-100
        return 'High';                                 // 101+
    }
}
