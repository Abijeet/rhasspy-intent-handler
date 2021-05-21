<?php
declare( strict_types = 1 );

namespace App\IntentHandlers;

use App\Models\Intent;
use App\QueryBuilders\QueryBuilder;
use App\QueryHandlers\WikipediaQueryHandler;

class WikipediaIntentHandler implements IntentHandler
{
    private const NAME = 'Wikipedia';

    public function __construct(
        private QueryBuilder $queryBuilder,
        private WikipediaQueryHandler $queryHandler

    ) {}

    public function is(Intent $intent): bool {
        return $intent->getName() === self::NAME;
    }

    public function getName(): string {
        return self::NAME;
    }

    public function handle(Intent $intent): void {
        // TODO:
        $searchText = $this->queryBuilder->get();
        $queryResult = $this->queryHandler->getQueryResult($searchText);
    }
}
