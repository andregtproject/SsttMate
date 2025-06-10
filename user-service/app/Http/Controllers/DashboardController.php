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
            $soundData = $soundServiceClient->getAllSoundData();
            $currentSoundLevel = $soundData['currentSoundLevel'] ?? null;
            
            return view('dashboard', [
                'soundData' => $currentSoundLevel,
                'connectionStatus' => $connectionTest['status'] ?? 'unknown',
                'connectionMessage' => $connectionTest['message'] ?? 'No connection info'
            ]);
            
        } catch (\Exception $e) {
            return view('dashboard', [
                'soundData' => null,
                'connectionStatus' => 'error',
                'connectionMessage' => 'Failed to connect to sound service: ' . $e->getMessage()
            ]);
        }
    }

    public function getSoundDataApi()
    {
        try {
            $soundServiceClient = new \UserService\Services\SoundServiceClient();
            $soundData = $soundServiceClient->getAllSoundData();
            
            return response()->json([
                'success' => true,
                'data' => $soundData['currentSoundLevel'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
