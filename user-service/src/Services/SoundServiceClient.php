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
            
            if ($data !== null) {
                return [
                    'status' => 'success',
                    'message' => 'Successfully connected to sound service',
                    'data' => $data
                ];
            }
            
            return [
                'status' => 'warning',
                'message' => 'Connected but no sound data available',
                'data' => null
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Connection failed: ' . $e->getMessage(),
                'data' => null
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
                }
            }
        ';
        
        return $this->executeQuery($query);
    }

    public function getUserSoundData(string $userId): array
    {
        // For now, return the current sound level as we don't have user-specific data
        return $this->getAllSoundData();
    }

    public function getDeviceSoundData(string $deviceId): array
    {
        // For now, return the current sound level as we don't have device-specific data
        return $this->getAllSoundData();
    }

    private function executeQuery(string $query, array $variables = []): array
    {
        try {
            $response = $this->client->post($this->baseUrl, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['errors'])) {
                error_log('Sound Service Client GraphQL Error: ' . json_encode($data['errors']));
                throw new \Exception('GraphQL Error from Sound Service: ' . ($data['errors'][0]['message'] ?? json_encode($data['errors'])));
            }

            return $data['data'] ?? [];
            
        } catch (RequestException $e) {
            $errorMessage = 'HTTP Request failed: ' . $e->getMessage();
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $errorMessage .= ' Response: ' . $responseBody;
            }
            error_log('Sound Service Client Error: ' . $errorMessage);
            throw new \Exception($errorMessage);
        } catch (\Exception $e) {
            error_log('Sound Service Client Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
