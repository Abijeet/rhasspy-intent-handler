<?php
declare(strict_types=1);

namespace Tests\IntentHandler;

use App\Exceptions\QueryResultNotFound;
use App\IntentHandlers\WikipediaIntentHandler;
use App\Models\Intent;
use App\SearchQuery\Builders\QueryBuilder;
use App\SearchQuery\Handlers\WikipediaQueryHandler;
use App\SearchQuery\Results\WikipediaQueryResult;
use Tests\TestCase;

class WikipediaIntentHandlerTest extends TestCase
{
	public function testEmptyQuery(): void
	{
		$queryString = '';
		$queryBuilder = $this->getQueryBuilder($queryString);

		$queryHandler = $this->getMockBuilder(WikipediaQueryHandler::class)
			->disableOriginalConstructor()
			->getMock();

		$queryHandler->expects($this->never())
			->method('getQueryResult');

		$intentHandler = new WikipediaIntentHandler($queryBuilder, $queryHandler);
		$result = $intentHandler->handle(
			new Intent(
				'wikipedia',
				0.6,
				[],
				'hello',
				'hello'
			)
		);

		$this->assertEquals(__('empty_search_query'), $result);
	}

	public function testQuery(): void
	{
		$queryString = 'new delhi';
		$queryResultString = 'extracted text';
		$queryBuilder = $this->getQueryBuilder($queryString);

		$result = $this->getMockBuilder(WikipediaQueryResult::class)
			->disableOriginalConstructor()
			->getMock();
		$result->expects($this->once())
			->method('getResult')
			->willReturn($queryResultString);

		$queryHandler = $this->getMockBuilder(WikipediaQueryHandler::class)
			->disableOriginalConstructor()
			->getMock();
		$queryHandler->expects($this->once())
			->method('getQueryResult')
			->willReturn($result);


		$intentHandler = new WikipediaIntentHandler($queryBuilder, $queryHandler);
		$result = $intentHandler->handle(
			new Intent(
				'wikipedia',
				0.5,
				[],
				'hello',
				'hello'
			)
		);

		$this->assertEquals($queryResultString, $result);
	}

	public function testQueryResultNotFound(): void
	{
		$queryString = 'new delhi';
		$queryBuilder = $this->getQueryBuilder($queryString);

		$queryHandler = $this->getMockBuilder(WikipediaQueryHandler::class)
			->disableOriginalConstructor()
			->getMock();
		$queryHandler->expects($this->once())
			->method('getQueryResult')
			->willThrowException(new QueryResultNotFound('not found'));

		$intentHandler = new WikipediaIntentHandler($queryBuilder, $queryHandler);
		$result = $intentHandler->handle(
			new Intent(
				'wikipedia',
				0.5,
				[],
				'hello',
				'hello'
			)
		);

		$this->assertEquals(__('rhasspy_wiki_query_not_found', ['query' => $queryString]), $result);
	}

	private function getQueryBuilder(string $query)
	{
		$queryBuilder = $this->getMockBuilder(QueryBuilder::class)
			->disableOriginalConstructor()
			->getMock();

		$queryBuilder->expects($this->once())
			->method('get')
			->will($this->returnValue($query));

		return $queryBuilder;
	}
}
