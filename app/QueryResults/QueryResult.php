<?php
declare( strict_types = 1 );

namespace App\QueryResults;

interface QueryResult {
    public function getResult(): ?string;
}
