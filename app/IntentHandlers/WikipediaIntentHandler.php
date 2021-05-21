<?php
declare( strict_types = 1 );

namespace App\IntentHandlers;

use App\Models\Intent;
use App\SearchQuery\Builders\QueryBuilder;
use App\SearchQuery\Handlers\WikipediaQueryHandler;

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

    public function handle(Intent $intent): string {
        // TODO:
        $searchText = $this->queryBuilder->get();
        $queryResult = $this->queryHandler->getQueryResult($searchText);
        return $queryResult->getResult();
    }
}
