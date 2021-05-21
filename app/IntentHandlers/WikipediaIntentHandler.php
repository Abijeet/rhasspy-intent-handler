<?php
declare( strict_types = 1 );

namespace App\IntentHandlers;

use App\Models\Intent;

class WikipediaIntentHandler implements IntentHandler
{
    private const NAME = 'Wikipedia';

    public function is(Intent $intent): bool {
        return $intent->getName() === self::NAME;
    }

    public function getName(): string {
        return self::NAME;
    }

    public function handle(Intent $intent): void {
        // TODO:
    }
}
