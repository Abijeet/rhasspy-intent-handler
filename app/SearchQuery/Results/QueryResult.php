<?php
declare(strict_types=1);

namespace App\SearchQuery\Results;

interface QueryResult
{
	public function getResult(): ?string;
}
