<?php
declare( strict_types = 1 );

namespace App\QueryBuilders;

class AudioQueryBuilder implements QueryBuilder {
    public function __construct(
        private string $recorder,
        private string $args,
        private string $fileFormat
    ) {}

    public function get(): string {
        return 'New Delhi';
    }
}
