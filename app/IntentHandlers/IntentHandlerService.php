<?php
declare(strict_types=1);

namespace App\IntentHandlers;

use App\Models\Intent;
use App\ResponseReporters\ResponseReporterFactory;
use Exception;

class IntentHandlerService
{
	public function __construct(
		private IntentHandlerFactory $factory,
		private ResponseReporterFactory $responseReporterFactory
	) {
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
			$response = $handler->handle($intent);
		} catch (Exception $e) {
			report($e);
			return __('rhasspy_unknown_processing_error');
		}

		// TODO: Perform this in the background
		// See: https://lumen.laravel.com/docs/8.x/queues
		$reporters = $this->responseReporterFactory->getAll();
		foreach ($reporters as $reporter) {
			$reporter->report($response, $intent);
		}

		return $response;
	}

	public function isIntentValid(Intent $intent): bool
	{
		return $this->factory->isValid($intent);
	}
}
