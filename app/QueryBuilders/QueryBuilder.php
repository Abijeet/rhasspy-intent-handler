<?php
declare( strict_types = 1 );

namespace App\QueryBuilders;

interface QueryBuilder {
    public function get(): string;
}
