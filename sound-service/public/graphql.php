<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use SoundService\GraphQL\Resolvers\SoundLevelResolver;
use SoundService\Services\FirebaseService; // For direct type definition if not using .graphql file

header('Content-Type: application/json; charset=utf-8');

try {
    // Initialize resolver
    $soundLevelResolver = new SoundLevelResolver();

    // Define SoundLevel type with all the fields the simulation returns
    $soundLevelType = new ObjectType([
        'name' => 'SoundLevel',
        'fields' => [
            'amplitude' => Type::int(),
            'isLoud' => Type::boolean(),
            'fetched_at' => Type::int(),
            'variation_applied' => Type::float(),
            'data_source' => Type::string(),
            'original_amplitude' => Type::int(),
            'scenario' => Type::string(),
            
            // Add simulation_info fields as individual fields for easier access
            'daily_pattern' => [
                'type' => Type::float(),
                'resolve' => function ($root) {
                    return $root['simulation_info']['daily_pattern'] ?? null;
                }
            ],
            'hourly_pattern' => [
                'type' => Type::float(),
                'resolve' => function ($root) {
                    return $root['simulation_info']['hourly_pattern'] ?? null;
                }
            ],
            'random_noise' => [
                'type' => Type::int(),
                'resolve' => function ($root) {
                    return $root['simulation_info']['random_noise'] ?? null;
                }
            ],
            'activity_level' => [
                'type' => Type::float(),
                'resolve' => function ($root) {
                    return $root['simulation_info']['activity_level'] ?? null;
                }
            ],
            'time_of_day' => [
                'type' => Type::int(),
                'resolve' => function ($root) {
                    return $root['simulation_info']['time_of_day'] ?? null;
                }
            ],
            
            // Add simulation_details fields for full simulation mode
            'base_level' => [
                'type' => Type::int(),
                'resolve' => function ($root) {
                    return $root['simulation_details']['base_level'] ?? null;
                }
            ],
            'wave_pattern' => [
                'type' => Type::float(),
                'resolve' => function ($root) {
                    return $root['simulation_details']['wave_pattern'] ?? null;
                }
            ],
            'micro_variation' => [
                'type' => Type::float(),
                'resolve' => function ($root) {
                    return $root['simulation_details']['micro_variation'] ?? null;
                }
            ],
            'random_spike' => [
                'type' => Type::int(),
                'resolve' => function ($root) {
                    return $root['simulation_details']['random_spike'] ?? null;
                }
            ],
        ]
    ]);
    
    $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => [
            'currentSoundLevel' => [
                'type' => $soundLevelType,
                'resolve' => function ($root, $args) use ($soundLevelResolver) {
                    return $soundLevelResolver->resolveCurrentSoundLevel();
                }
            ],
        ]
    ]);

    $schema = new Schema([
        'query' => $queryType
    ]);

    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'] ?? null;
    $variableValues = $input['variables'] ?? null;

    if (empty($query)) {
        throw new \InvalidArgumentException('GraphQL query is missing.');
    }

    $result = GraphQL::executeQuery($schema, $query, null, null, $variableValues);
    $output = $result->toArray();

} catch (\Exception $e) {
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage()
            ]
        ]
    ];
}

echo json_encode($output);
