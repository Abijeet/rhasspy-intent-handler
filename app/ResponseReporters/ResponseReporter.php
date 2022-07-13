<?php
declare(strict_types=1);

namespace App\ResponseReporters;

use App\Models\Intent;

interface ResponseReporter
{
	public function report(string $message, Intent $intent): bool;
}
