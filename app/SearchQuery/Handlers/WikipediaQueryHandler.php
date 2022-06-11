<?php
declare(strict_types=1);

namespace App\SearchQuery\Handlers;

use App\Exceptions\QueryResultError;
use App\Exceptions\QueryResultNotFound;
use App\SearchQuery\Results\WikipediaQueryResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class WikipediaQueryHandler implements QueryHandler
{
	private const API = 'https://en.wikipedia.org/api/rest_v1/page/summary/';

	public function getQueryResult(string $query): WikipediaQueryResult
	{
		try {
			// Trim and clean up the search query before pinging Wikipedia
			$query = trim(rtrim($query, ".\n"));
			info("Querying Wikipedia for $query");

			// https://en.wikipedia.org/api/rest_v1/page/summary/Gargoyle
			$client = new Client();
			$response = $client->request('GET', self::API . str_replace(' ', '_', $query), [
				'headers' => [
					'Accept' => 'application/json',
				]
			]);

			$body = $response->getBody()->getContents();
			$jsonData = json_decode($body, true);
			return WikipediaQueryResult::fromJson($jsonData);
		} catch (BadResponseException $e) {
			if ($e->getCode() === 404) {
				throw new QueryResultNotFound("The Wikipedia article was not found for: $query");
			}

			$httpCode = $e->getCode();
			$body = $e->hasResponse() ? $e->getResponse()->getBody() : 'N/A';
			throw new QueryResultError(
				"There was an error while fetching the Wikipedia article for: $query." .
				" HTTP Code: $httpCode;\n Response: $body"
			);
		}
	}
}
