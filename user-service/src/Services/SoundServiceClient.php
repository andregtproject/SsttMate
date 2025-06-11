<?php

namespace UserService\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SoundServiceClient
{
    private Client $client;
    private string $baseUrl;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'connect_timeout' => 5,
            'verify' => false // For development only
        ]);
        
        // Use container name for inter-service communication
        $this->baseUrl = 'http://sound-service/graphql.php';
    }

    public function testConnection(): array
    {
        $query = '
            query TestConnection {
                currentSoundLevel {
                    amplitude
                    isLoud
                }
            }
        ';

        try {
            $result = $this->executeQuery($query);
            $data = $result['currentSoundLevel'] ?? null;
            
            // Add debug logging
            error_log('TestConnection - Raw result: ' . json_encode($result));
            error_log('TestConnection - Extracted data: ' . json_encode($data));
            
            if ($data !== null && isset($data['amplitude'])) {
                // Ensure amplitude is valid before calculation
                $amplitude = (int)$data['amplitude'];
                $db = $amplitude > 0 ? round(20 * log10($amplitude / 100), 1) : 0; // Changed divisor for better dB range
                
                return [
                    'status' => 'success',
                    'message' => 'Data berhasil diambil dari Firebase!',
                    'data' => [
                        'amplitude' => $amplitude,
                        'db' => $db,
                        'isLoud' => (bool)($data['isLoud'] ?? false)
                    ]
                ];
            }
            
            return [
                'status' => 'warning',
                'message' => 'Connected but no sound data available',
                'data' => [
                    'amplitude' => 0,
                    'db' => 0,
                    'isLoud' => false
                ]
            ];
        } catch (\Exception $e) {
            error_log('TestConnection failed: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Connection failed: ' . $e->getMessage(),
                'data' => [
                    'amplitude' => 0,
                    'db' => 0,
                    'isLoud' => false
                ]
            ];
        }
    }

    public function getAllSoundData(): array
    {
        $query = '
            query GetCurrentSoundLevel {
                currentSoundLevel {
                    amplitude
                    isLoud
                    fetched_at
                    data_source
                    scenario
                    original_amplitude
                    daily_pattern
                    activity_level
                    time_of_day
                }
            }
        ';
        
        try {
            $result = $this->executeQuery($query);
            $data = $result['currentSoundLevel'] ?? null;
            
            // Add debug logging
            error_log('getAllSoundData - Raw result: ' . json_encode($result));
            error_log('getAllSoundData - Extracted data: ' . json_encode($data));
            
            if ($data !== null && isset($data['amplitude'])) {
                // Ensure amplitude is valid
                $amplitude = (int)$data['amplitude'];
                $db = $amplitude > 0 ? round(20 * log10($amplitude / 100), 1) : 0;
                
                $response = [
                    'status' => 'success',
                    'message' => 'Data berhasil diambil dari Firebase!',
                    'data' => [
                        'amplitude' => $amplitude,
                        'db' => $db,
                        'isLoud' => (bool)($data['isLoud'] ?? false),
                        'timestamp' => time(),
                        'data_source' => $data['data_source'] ?? 'unknown',
                        'fetched_at' => $data['fetched_at'] ?? null,
                        'scenario' => $data['scenario'] ?? null,
                        'original_amplitude' => $data['original_amplitude'] ?? null,
                        'activity_level' => $data['activity_level'] ?? null,
                        'time_of_day' => $data['time_of_day'] ?? null,
                    ]
                ];
                
                error_log('getAllSoundData - Final response: ' . json_encode($response));
                return $response;
            }
            
            // Return default data structure when no data available
            $defaultResponse = [
                'status' => 'warning',
                'message' => 'No data available from Firebase',
                'data' => [
                    'amplitude' => 0,
                    'db' => 0,
                    'isLoud' => false,
                    'timestamp' => time(),
                    'data_source' => 'default'
                ]
            ];
            
            return $defaultResponse;
            
        } catch (\Exception $e) {
            error_log('getAllSoundData failed: ' . $e->getMessage());
            
            $errorResponse = [
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage(),
                'data' => [
                    'amplitude' => 0,
                    'db' => 0,
                    'isLoud' => false,
                    'timestamp' => time(),
                    'data_source' => 'error'
                ]
            ];
            
            return $errorResponse;
        }
    }

    public function getUserSoundData(string $userId): array
    {
        $result = $this->getAllSoundData();
        if ($result['status'] === 'success' && $result['data']) {
            $result['data']['userId'] = $userId;
            $result['data']['timestamp'] = time();
        }
        return $result;
    }

    public function getDeviceSoundData(string $deviceId): array
    {
        $result = $this->getAllSoundData();
        if ($result['status'] === 'success' && $result['data']) {
            $result['data']['deviceId'] = $deviceId;
            $result['data']['timestamp'] = time();
        }
        return $result;
    }

    private function executeQuery(string $query, array $variables = []): array
    {
        $maxRetries = 3;
        $retryDelay = 1; // seconds
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                // Add debug logging for the request
                error_log("Executing GraphQL query (attempt {$attempt}/{$maxRetries}) to: " . $this->baseUrl);
                error_log('Query: ' . $query);
                
                $response = $this->client->post($this->baseUrl, [
                    'json' => [
                        'query' => $query,
                        'variables' => $variables
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'User-Agent' => 'UserService/1.0'
                    ]
                ]);

                $responseBody = $response->getBody()->getContents();
                error_log('GraphQL Response Body: ' . $responseBody);
                
                $data = json_decode($responseBody, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
                }
                
                if (isset($data['errors'])) {
                    error_log('Sound Service Client GraphQL Error: ' . json_encode($data['errors']));
                    throw new \Exception('GraphQL Error from Sound Service: ' . ($data['errors'][0]['message'] ?? json_encode($data['errors'])));
                }

                $result = $data['data'] ?? [];
                error_log('executeQuery - Parsed result: ' . json_encode($result));
                
                // Success - return result
                return $result;
                
            } catch (RequestException $e) {
                $errorMessage = 'HTTP Request failed: ' . $e->getMessage();
                if ($e->hasResponse()) {
                    $responseBody = $e->getResponse()->getBody()->getContents();
                    $errorMessage .= ' Response: ' . $responseBody;
                    error_log('HTTP Response Body: ' . $responseBody);
                }
                error_log("Sound Service Client HTTP Error (attempt {$attempt}/{$maxRetries}): " . $errorMessage);
                
                // If this is the last attempt, throw the error
                if ($attempt === $maxRetries) {
                    throw new \Exception($errorMessage);
                }
                
                // Wait before retrying
                sleep($retryDelay);
                $retryDelay *= 2; // Exponential backoff
                
            } catch (\Exception $e) {
                error_log("Sound Service Client General Error (attempt {$attempt}/{$maxRetries}): " . $e->getMessage());
                
                // If this is the last attempt, throw the error
                if ($attempt === $maxRetries) {
                    throw $e;
                }
                
                // Wait before retrying
                sleep($retryDelay);
                $retryDelay *= 2; // Exponential backoff
            }
        }
        
        // This should never be reached due to the throw statements above
        throw new \Exception('Maximum retry attempts exceeded');
    }
}
