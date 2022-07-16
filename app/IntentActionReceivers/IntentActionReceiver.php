<?php
declare(strict_types=1);

namespace App\IntentActionReceivers;

interface IntentActionReceiver
{
	public function get(): string;
}
