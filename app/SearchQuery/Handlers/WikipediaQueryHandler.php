<?php
declare( strict_types = 1 );

namespace App\SearchQuery\Handlers;

use App\Exceptions\QueryResultError;
use App\Exceptions\QueryResultNotFound;
use App\SearchQuery\Results\WikipediaQueryResult;
use GuzzleHttp\Client;

class WikipediaQueryHandler implements QueryHandler {
    private const API = 'https://en.wikipedia.org/api/rest_v1/page/summary/';

    public function getQueryResult(string $query): WikipediaQueryResult {
        // https://en.wikipedia.org/api/rest_v1/page/summary/Gargoyle
        $client = new Client();
        $response = $client->request('GET', self::API . str_replace(' ', '_', $query), [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        $httpCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        if ($httpCode === 200) {
            $jsonData = json_decode($body, true);
            return WikipediaQueryResult::fromJson( $jsonData );
        } else if ($httpCode === 404) {
            throw new QueryResultNotFound("The Wikipedia article was not found for: $query");
        } else {
            throw new QueryResultError(
                "There was an error while fetching the Wikipedia article for: $query." .
                " HTTP Code: $httpCode;\n Response: $body"
            );
        }
    }
}
