<?php
declare( strict_types = 1 );

namespace App\IntentHandlers;

use App\Models\Intent;

interface IntentHandler
{
    public function is(Intent $intent): bool;

    public function getName(): string;

    public function handle(Intent $intent): void;
}
