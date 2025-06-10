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
        // Check if Firebase library is available
        if (!class_exists('Kreait\Firebase\Factory')) {
            throw new \RuntimeException('Firebase library (kreait/firebase-php) is not available. Please ensure it is properly installed.');
        }

        $databaseUrl = getenv('FIREBASE_DATABASE_URL');
        if (!$databaseUrl) {
            throw new \RuntimeException('FIREBASE_DATABASE_URL environment variable is not set.');
        }

        $firebaseFactory = (new Factory())->withDatabaseUri($databaseUrl);

        // Handle service account
        $serviceAccountPathFromEnv = getenv('FIREBASE_CREDENTIALS');
        $defaultServiceAccountPath = __DIR__ . '/../../config/firebase_credentials.json';

        $serviceAccountToUse = null;

        if ($serviceAccountPathFromEnv && file_exists($serviceAccountPathFromEnv)) {
            $serviceAccountToUse = $serviceAccountPathFromEnv;
        } elseif (file_exists($defaultServiceAccountPath)) {
            $serviceAccountToUse = $defaultServiceAccountPath;
        } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS') && file_exists(getenv('GOOGLE_APPLICATION_CREDENTIALS'))) {
            // Let kreait/firebase-php pick it up automatically
        }

        if ($serviceAccountToUse) {
            try {
                $firebaseFactory = $firebaseFactory->withServiceAccount($serviceAccountToUse);
                error_log("FirebaseService: Successfully loaded service account from: " . $serviceAccountToUse);
            } catch (\Exception $e) {
                error_log("FirebaseService: Failed to load service account from '{$serviceAccountToUse}': " . $e->getMessage());
            }
        } else {
            error_log("FirebaseService: No service account credentials configured.");
        }
        
        $this->database = $firebaseFactory->createDatabase();
        error_log("FirebaseService: Successfully initialized Firebase database connection");
    }

    /**
     * Fetches the current sound level data (amplitude and isLoud) from the 'sound_level' path.
     *
     * @return array|null The sound level data (e.g., ['amplitude' => 79, 'isLoud' => false]) or null if not found or an error occurs.
     */
    public function getCurrentSoundLevel(): ?array
    {
        try {
            error_log("FirebaseService: Attempting to fetch data from 'sound_level' path");
            
            $reference = $this->database->getReference('sound_level');
            $snapshot = $reference->getSnapshot();

            if ($snapshot->exists()) {
                $data = $snapshot->getValue();
                error_log("FirebaseService: Successfully retrieved data: " . json_encode($data));
                return $data;
            }
            error_log("FirebaseService: No data found at 'sound_level' path.");
            return null;
        } catch (ReferenceNotFound $e) {
            error_log("FirebaseService: 'sound_level' path not found in Firebase. " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log('FirebaseService Error fetching current sound level: ' . $e->getMessage());
            error_log('FirebaseService Error trace: ' . $e->getTraceAsString());
            return null;
        }
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
}
