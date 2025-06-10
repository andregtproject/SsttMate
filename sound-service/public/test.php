<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Ensure correct path to autoload

// Use Guzzle for HTTP requests if you don't have it, run: composer require guzzlehttp/guzzle
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Firebase Sound Level Test via GraphQL</h1>";

$graphQLEndpoint = 'http://localhost/graphql.php'; // Adjust if your local setup differs (e.g. port)
// If running inside Docker and sound-service is accessible via a different hostname from where test.php is executed, adjust.
// Assuming test.php is accessed via browser hitting the sound-service container directly.

$guzzleClient = new Client();

$query = <<<'GRAPHQL'
query {
  currentSoundLevel {
    amplitude
    isLoud
  }
}
GRAPHQL;

/*
// Example query for a list if you implement it later
$queryList = <<<'GRAPHQL'
query GetSoundLevels($userId: String) {
  soundLevels(user_id: $userId) {
    amplitude
    isLoud
  }
}
GRAPHQL;
$variablesList = ['userId' => 'user_123'];
*/


try {
    echo "<h2>Sending GraphQL Query to: " . htmlspecialchars($graphQLEndpoint) . "</h2>";
    echo "<h3>Query:</h3>";
    echo "<pre>" . htmlspecialchars($query) . "</pre>";

    $response = $guzzleClient->post($graphQLEndpoint, [
        'json' => [
            'query' => $query,
            // 'variables' => $variablesList // if using variables
        ]
    ]);

    $body = $response->getBody()->getContents();
    $data = json_decode($body, true);

    echo "<h3>GraphQL Response:</h3>";
    echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . "</pre>";

    if (isset($data['data']['currentSoundLevel']) && $data['data']['currentSoundLevel'] !== null) {
        echo "<h3>Parsed Values:</h3>";
        $currentSoundLevel = $data['data']['currentSoundLevel'];
        echo "<p><strong>Amplitude:</strong> " . htmlspecialchars($currentSoundLevel['amplitude'] ?? 'N/A') . "</p>";
        echo "<p><strong>Is Loud:</strong> " . (isset($currentSoundLevel['isLoud']) ? ($currentSoundLevel['isLoud'] ? 'Yes' : 'No') : 'N/A') . "</p>";
    } elseif (isset($data['errors'])) {
        echo "<p style='color:red;'>GraphQL query returned errors.</p>";
    } else {
        echo "<p style='color:orange;'>Could not retrieve 'currentSoundLevel' data from GraphQL response, or it was null.</p>";
    }
    
    echo "<hr>";
    echo "<h3 style='color:green;'>Test Script Completed.</h3>";
    echo "<p><strong>Important:</strong> If data is not appearing, check the Docker container logs for 'sound-service' for any error messages from FirebaseService or GraphQL execution.</p>";

} catch (RequestException $e) {
    echo "<h2 style='color:red;'>HTTP Request Error:</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    if ($e->hasResponse()) {
        echo "<h3>Response Body:</h3>";
        echo "<pre>" . htmlspecialchars($e->getResponse()->getBody()->getContents()) . "</pre>";
    }
} catch (\Exception $e) {
    echo "<h2 style='color:red;'>An Error Occurred:</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
