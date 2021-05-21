<?php
declare( strict_types = 1 );

namespace App\QueryHandlers;

use App\QueryResults\QueryResult;

interface QueryHandler {
    public function getQueryResult(string $query): QueryResult;
}
