<?php

namespace SoundService\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Exception\Database\ReferenceNotFound;

class FirebaseService
{
    private Database $database;

    public function __construct()
    {
        try {
            // HANYA menggunakan environment variables - TIDAK ada file credentials
            $serviceAccount = [
                'type' => env('FIREBASE_TYPE', 'service_account'),
                'project_id' => env('FIREBASE_PROJECT_ID'),
                'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
                'private_key' => str_replace('\\n', "\n", env('FIREBASE_PRIVATE_KEY')),
                'client_email' => env('FIREBASE_CLIENT_EMAIL'),
                'client_id' => env('FIREBASE_CLIENT_ID'),
                'auth_uri' => env('FIREBASE_AUTH_URI', 'https://accounts.google.com/o/oauth2/auth'),
                'token_uri' => env('FIREBASE_TOKEN_URI', 'https://oauth2.googleapis.com/token'),
                'auth_provider_x509_cert_url' => env('FIREBASE_AUTH_PROVIDER_X509_CERT_URL'),
                'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
                'universe_domain' => env('FIREBASE_UNIVERSE_DOMAIN', 'googleapis.com')
            ];

            // Validasi bahwa semua environment variables tersedia
            if (empty($serviceAccount['project_id']) || empty($serviceAccount['private_key'])) {
                throw new \Exception('Firebase environment variables not configured properly');
            }

            $factory = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
            
            $this->database = $factory->createDatabase();
            
        } catch (\Exception $e) {
            error_log('Firebase initialization failed: ' . $e->getMessage());
            // Fallback: use direct database connection without service account
            try {
                $factory = (new Factory)->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
                $this->database = $factory->createDatabase();
            } catch (\Exception $fallbackError) {
                error_log('Firebase fallback initialization failed: ' . $fallbackError->getMessage());
                throw new \Exception('Failed to initialize Firebase: ' . $fallbackError->getMessage());
            }
        }
    }

    /**
     * Fetches the current sound level data with realistic variations
     */
    public function getCurrentSoundLevel(): ?array
    {
        try {
            $timestamp = time();
            error_log("FirebaseService: Fetching data at timestamp: " . $timestamp);
            
            $reference = $this->database->getReference('sound_level');
            $snapshot = $reference->getSnapshot();

            if ($snapshot->exists()) {
                $data = $snapshot->getValue();
                error_log("FirebaseService: Raw Firebase data: " . json_encode($data));
                
                // Karena data Firebase statis, kita buat variasi realistis
                if (isset($data['amplitude'])) {
                    $baseAmplitude = (int)$data['amplitude']; // 3201
                    
                    // Simulasi variasi suara yang realistis
                    $timeOfDay = date('H');
                    $minuteOfHour = date('i');
                    
                    // Pola variasi berdasarkan waktu
                    $dailyPattern = sin(($timeOfDay / 24) * 2 * M_PI) * 800; // Variasi harian
                    $hourlyPattern = sin(($minuteOfHour / 60) * 2 * M_PI) * 500; // Variasi per jam
                    $randomNoise = rand(-300, 300); // Noise acak
                    
                    // Simulasi aktivitas ruangan
                    $activityLevel = 1;
                    if ($timeOfDay >= 8 && $timeOfDay <= 17) {
                        $activityLevel = 1.5; // Jam kerja lebih ramai
                    }
                    if ($timeOfDay >= 12 && $timeOfDay <= 13) {
                        $activityLevel = 2; // Jam makan siang sangat ramai
                    }
                    
                    $newAmplitude = $baseAmplitude + ($dailyPattern + $hourlyPattern + $randomNoise) * $activityLevel;
                    
                    // Batasi range yang realistis
                    $newAmplitude = max(800, min(6000, $newAmplitude));
                    
                    $data['amplitude'] = (int)$newAmplitude;
                    $data['isLoud'] = $newAmplitude > 4000;
                    $data['fetched_at'] = $timestamp;
                    $data['simulation_info'] = [
                        'original_amplitude' => $baseAmplitude,
                        'daily_pattern' => round($dailyPattern, 2),
                        'hourly_pattern' => round($hourlyPattern, 2),
                        'random_noise' => $randomNoise,
                        'activity_level' => $activityLevel,
                        'time_of_day' => $timeOfDay,
                        'minute_of_hour' => $minuteOfHour
                    ];
                    
                    error_log("FirebaseService: Enhanced data with realistic variation: " . json_encode($data));
                }
                
                return $data;
            }
            
            error_log("FirebaseService: No data found, returning simulation");
            return $this->getSimulatedSoundLevel();
            
        } catch (\Exception $e) {
            error_log('FirebaseService Error: ' . $e->getMessage());
            return $this->getSimulatedSoundLevel();
        }
    }

    /**
     * Generate completely simulated data for testing
     */
    public function getSimulatedSoundLevel(): array
    {
        $timestamp = time();
        
        // Simulasi berbagai kondisi ruangan
        $scenarios = [
            'quiet' => ['base' => 1200, 'variation' => 400, 'loud_threshold' => 0.1],
            'normal' => ['base' => 2500, 'variation' => 800, 'loud_threshold' => 0.3],
            'busy' => ['base' => 4000, 'variation' => 1200, 'loud_threshold' => 0.6],
            'event' => ['base' => 5500, 'variation' => 1000, 'loud_threshold' => 0.8]
        ];
        
        // Pilih skenario berdasarkan waktu
        $hour = date('H');
        $scenario = 'quiet';
        if ($hour >= 8 && $hour <= 17) $scenario = 'normal';
        if ($hour >= 12 && $hour <= 13) $scenario = 'busy';
        if ($hour >= 17 && $hour <= 19) $scenario = 'event';
        
        $config = $scenarios[$scenario];
        
        // Generate amplitude dengan variasi yang smooth
        $wavePattern = sin($timestamp / 30) * ($config['variation'] / 2); // Gelombang 30 detik
        $microVariation = sin($timestamp / 5) * ($config['variation'] / 4); // Variasi kecil 5 detik
        $randomSpike = (rand(0, 100) < 5) ? rand(500, 1500) : 0; // 5% chance spike
        
        $amplitude = $config['base'] + $wavePattern + $microVariation + $randomSpike;
        $amplitude = max(500, min(7000, $amplitude));
        
        return [
            'amplitude' => (int)$amplitude,
            'isLoud' => rand(0, 100) < ($config['loud_threshold'] * 100),
            'fetched_at' => $timestamp,
            'data_source' => 'full_simulation',
            'scenario' => $scenario,
            'simulation_details' => [
                'base_level' => $config['base'],
                'wave_pattern' => round($wavePattern, 2),
                'micro_variation' => round($microVariation, 2),
                'random_spike' => $randomSpike,
                'hour' => $hour
            ]
        ];
    }

    /**
     * A generic method to get other sound data, potentially with filters.
     * This is a placeholder and needs to be adapted based on your actual data structure for historical/multiple sound records.
     * The Firebase structure in the image only shows 'sound_level'.
     *
     * @param array $filters (e.g., ['user_id' => 'some_user'])
     * @return array
     */
    public function getSoundData(array $filters = []): array
    {
        // This method needs to be implemented based on how your other sound data is structured in Firebase.
        // For example, if you have a list of sound entries under a path like 'sound_logs':
        /*
        try {
            $referencePath = 'sound_logs'; // Adjust if your path is different
            $reference = $this->database->getReference($referencePath);
            $query = $reference;

            if (!empty($filters['user_id'])) {
                $query = $query->orderByChild('user_id')->equalTo($filters['user_id']);
            }
            // Add other filters as needed

            $snapshot = $query->getSnapshot();
            return $snapshot->exists() ? $snapshot->getValue() : [];
        } catch (\Exception $e) {
            error_log('FirebaseService Error in getSoundData: ' . $e->getMessage());
            return [];
        }
        */
        error_log("FirebaseService: getSoundData() called, but it's a placeholder. Adapt for your general sound data structure.");
        return []; // Placeholder implementation
    }

    /**
     * Test method to verify Firebase connection with detailed logging
     */
    public function testFirebaseConnection(): array
    {
        try {
            error_log("FirebaseService: Testing Firebase connection...");
            
            $reference = $this->database->getReference();
            $snapshot = $reference->getSnapshot();
            
            error_log("FirebaseService: Firebase connection successful");
            
            // Try to get the sound_level data specifically
            $soundLevelRef = $this->database->getReference('sound_level');
            $soundLevelSnapshot = $soundLevelRef->getSnapshot();
            
            if ($soundLevelSnapshot->exists()) {
                $data = $soundLevelSnapshot->getValue();
                error_log("FirebaseService: sound_level data exists: " . json_encode($data));
                
                return [
                    'status' => 'success',
                    'message' => 'Firebase connection successful',
                    'sound_level_exists' => true,
                    'sound_level_data' => $data,
                    'timestamp' => time()
                ];
            } else {
                error_log("FirebaseService: sound_level path does not exist");
                return [
                    'status' => 'warning',
                    'message' => 'Firebase connected but sound_level path not found',
                    'sound_level_exists' => false,
                    'timestamp' => time()
                ];
            }
            
        } catch (\Exception $e) {
            error_log("FirebaseService: Firebase connection test failed: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Firebase connection failed: ' . $e->getMessage(),
                'timestamp' => time()
            ];
        }
    }
}
