<?php
declare(strict_types=1);

namespace App\ResponseReporters;

use Illuminate\Contracts\Container\Container;

class ResponseReporterFactory
{
	public function __construct(
		private Container $app,
		private array $configuredReporters
	) {
	}

	/** @return ResponseReporter[] */
	public function getAll(): array
	{
		$activeReporters = [];
		foreach ($this->configuredReporters as $reporter) {
			if ($reporter['enabled'] === true) {
				$activeReporters[] = $this->app->make($reporter['class']);
			}
		}

		return $activeReporters;
	}
}
