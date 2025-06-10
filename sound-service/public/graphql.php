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

    // Define SoundLevel type programmatically (alternative to loading from .graphql file for simplicity here)
    $soundLevelType = new ObjectType([
        'name' => 'SoundLevel',
        'fields' => [
            'amplitude' => Type::int(),
            'isLoud' => Type::boolean(),
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
            // If you had soundLevels(user_id: String): [SoundLevel]
            // 'soundLevels' => [
            //     'type' => Type::listOf($soundLevelType),
            //     'args' => [
            //         'user_id' => Type::string(),
            //         'device_id' => Type::string(),
            //     ],
            //     'resolve' => function ($root, $args) use ($soundLevelResolver) {
            //         // return $soundLevelResolver->resolveSoundLevels($root, $args);
            //         // Placeholder:
            //         return [];
            //     }
            // ]
        ]
    ]);

    $schema = new Schema([
        'query' => $queryType
    ]);

    // Or, to load from .graphql file (more robust for larger schemas)
    // $typeRegistry = new \GraphQL\Utils\TypeRegistry();
    // $schemaFileContents = file_get_contents(__DIR__ . '/../graphql/schema.graphql');
    // $schema = \GraphQL\Utils\BuildSchema::build($schemaFileContents, $typeRegistry);
    //
    // // You would then need to associate resolvers, for example:
    // $resolvers = [
    //    'Query' => [
    //        'currentSoundLevel' => [$soundLevelResolver, 'resolveCurrentSoundLevel'],
    //        // 'soundLevels' => [$soundLevelResolver, 'resolveSoundLevels'],
    //    ],
    // ];
    // // And use $schema->setFieldResolver($callableProvider) or similar mechanism
    // // For webonyx/graphql-php, resolvers are often passed during execution or schema setup.
    // // The programmatic definition above is simpler for this specific case.


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
    // Log the full error for debugging
    error_log("GraphQL Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
}

echo json_encode($output);
