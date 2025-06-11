<?php

namespace SoundService\GraphQL\Resolvers;

use SoundService\Services\FirebaseService;

class SoundLevelResolver
{
    private FirebaseService $firebaseService;

    public function __construct()
    {
        $this->firebaseService = new FirebaseService();
    }

    public function resolveCurrentSoundLevel(): ?array
    {
        return $this->firebaseService->getCurrentSoundLevel();
    }

    // Example for a list, if your Firebase structure supports it later
    // public function resolveSoundLevels($root, array $args): array
    // {
    //     $filters = [];
    //     if (!empty($args['user_id'])) {
    //         $filters['user_id'] = $args['user_id'];
    //     }
    //     if (!empty($args['device_id'])) {
    //         $filters['device_id'] = $args['device_id'];
    //     }
    //     // This would call a method in FirebaseService designed to fetch a list
    //     // e.g., return $this->firebaseService->getSoundLevelHistory($filters);
    //     // For now, as per schema, we only have currentSoundLevel
    //     return [];
    // }
}
