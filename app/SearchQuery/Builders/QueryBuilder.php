<?php
declare(strict_types=1);

namespace App\SearchQuery\Builders;

interface QueryBuilder
{
	public function get(): string;
}
