<?php
declare(strict_types=1);

namespace App\NaturalLanguageProcessors;

use DateTime;

interface NaturalLanguageProcessor
{
	/** @return DateTime[] Sorted */
	public function getDateTime(string $text): array;
}
