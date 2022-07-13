<?php
declare(strict_types=1);

use App\ResponseReporters\TelegramResponseReporter;

return [
	'available' => [
		[
			'class' => TelegramResponseReporter::class,
			'enabled' => true
		]
	]
];
