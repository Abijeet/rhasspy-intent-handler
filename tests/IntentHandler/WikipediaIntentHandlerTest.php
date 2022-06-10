<?php
declare(strict_types=1);

namespace Tests\IntentHandler;

use App\SearchQuery\Builders\QueryBuilder;
use Tests\TestCase;

class WikipediaIntentHandlerTest extends TestCase
{
	public function testQueryResultNotFound()
	{
		$queryBuilder = $this->getMockBuilder(QueryBuilder::class)
			->disableOriginalConstructor()
			->getMock();

		$queryBuilder->expects($this->once())
			->method('get')
			->will($this->returnValue('Invalid string 1234545'));
	}
}
