<?php
declare( strict_types = 1 );

namespace App\SearchQuery\Handlers;

use App\SearchQuery\Results\QueryResult;

interface QueryHandler {
    public function getQueryResult(string $query): QueryResult;
}
