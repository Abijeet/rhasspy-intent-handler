<?php
declare(strict_types=1);

namespace Tests\IntentHandler;

use App\IntentHandlers\WikipediaIntentHandler;
use App\Models\Intent;
use App\SearchQuery\Builders\QueryBuilder;
use App\SearchQuery\Handlers\WikipediaQueryHandler;
use Tests\TestCase;

class WikipediaIntentHandlerTest extends TestCase
{
	public function testQueryResultNotFound()
	{
		$queryString = 'search string';
		$queryBuilder = $this->getMockBuilder(QueryBuilder::class)
			->disableOriginalConstructor()
			->getMock();

		$queryBuilder->expects($this->once())
			->method('get')
			->will($this->returnValue($queryString));

		$queryHandler = $this->getMockBuilder(WikipediaQueryHandler::class)
			->disableOriginalConstructor()
			->getMock();

		$queryHandler->expects($this->once())
			->method('getQueryResult')
			->with($queryString);

		$intentHandler = new WikipediaIntentHandler($queryBuilder, $queryHandler);
		$intentHandler->handle(
			new Intent(
				'wikipedia',
				0.6,
				[],
				'hello',
				'hello'
			)
		);
	}
}
