<?php
declare( strict_types = 1 );

namespace App\IntentHandlers;

use App\Models\Intent;

class IntentHandlerService
{
    public function __construct(private IntentHandlerFactory $factory) {}

    public function handle(Intent $intent): void {
        $handler = $this->factory->getHandler($intent);
        $handler->handle($intent);
    }

    public function isIntentValid(Intent $intent): bool {
        return $this->factory->isValid($intent);
    }
}
