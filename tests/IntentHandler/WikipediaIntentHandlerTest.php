<?php
declare( strict_types = 1 );

use App\SearchQuery\Builders\QueryBuilder;
use Laravel\Lumen\Testing\TestCase;

class WikipediaIntentHandlerTest extends TestCase {
    public function testQueryResultNotFound() {
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilder->expects( $this->once() )
            ->method( 'get' )
            ->will( $this->returnValue( 'Invalid string 1234545' ) );

        $query

    }
}