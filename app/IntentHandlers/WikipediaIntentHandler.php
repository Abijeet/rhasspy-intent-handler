<?php
declare( strict_types = 1 );

namespace App\IntentHandlers;

use App\Exceptions\QueryResultError;
use App\Exceptions\QueryResultNotFound;
use App\Models\Intent;
use App\SearchQuery\Builders\QueryBuilder;
use App\SearchQuery\Handlers\WikipediaQueryHandler;
use Exception;

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
        $searchText = $this->queryBuilder->get();

        try {
            $queryResult = $this->queryHandler->getQueryResult($searchText);
            return $queryResult->getResult();
        } catch (QueryResultNotFound $e) {
            return __('rhasspy_wiki_query_not_found', ['query' => $searchText]);
        } catch (QueryResultError $e) {
            report($e);
            return __('rhasspy_wiki_query_error', ['query' => $searchText]);
        } catch (Exception $e) {
            report($e);
            return __('rhasspy_unknown_processing_error');
        }
    }
}
