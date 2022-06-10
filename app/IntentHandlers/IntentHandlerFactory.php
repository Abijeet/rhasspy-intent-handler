<?php
declare(strict_types=1);

namespace App\IntentHandlers;

use App\Models\Intent;
use InvalidArgumentException;
use RuntimeException;

class IntentHandlerFactory
{
	private const HANDLERS = [WikipediaIntentHandler::class];

	public function getHandler(Intent $intent): IntentHandler
	{
		$intentHandler = $this->identifyHandler($intent);
		if (!$intentHandler) {
			throw new InvalidArgumentException('No IntentHandler identified for: ' . $intent->getName());
		}

		return $intentHandler;
	}

	public function isValid(Intent $intent): bool
	{
		$intentHandler = $this->identifyHandler($intent);
		return $intentHandler !== null;
	}

	private function identifyHandler(Intent $intent): ?IntentHandler
	{
		foreach (self::HANDLERS as $handler) {
			$instance = app($handler);
			if (!$instance instanceof IntentHandler) {
				throw new RuntimeException("$handler does not implement IntentHandler.");
			}

			if ($instance->is($intent)) {
				return $instance;
			}
		}
	}
}
