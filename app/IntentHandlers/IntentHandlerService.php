<?php
declare(strict_types=1);

namespace App\IntentHandlers;

use App\Models\Intent;
use Exception;

class IntentHandlerService
{
	public function __construct(private IntentHandlerFactory $factory)
	{
	}

	public function handle(Intent $intent): string
	{
		if (!$this->isIntentValid($intent)) {
			return __('rhasspy_error_unknown');
		}

		if (!$intent->isConfident()) {
			return __('rhasspy_error_confused');
		}

		try {
			$handler = $this->factory->getHandler($intent);
			return $handler->handle($intent);
		} catch (Exception $e) {
			report($e);
			return __('rhasspy_unknown_processing_error');
		}
	}

	public function isIntentValid(Intent $intent): bool
	{
		return $this->factory->isValid($intent);
	}
}
