<?php

namespace SCQ\Tests;

use SCQ\CompoundQueryResult;

/**
 * @covers \SCQ\CompoundQueryResult
 * @group semantic-compound-queries
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */
class CompoundQueryResultTest extends \PHPUnit_Framework_TestCase {

	private $store;
	private $query;

	protected function setUp() {
		parent::setUp();

		$this->store = $this->getMockBuilder( '\SMW\Store' )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->query = $this->getMockBuilder( '\SMWQuery' )
			->disableOriginalConstructor()
			->getMock();
	}

	public function testCanConstruct() {

		$this->assertInstanceOf(
			CompoundQueryResult::class,
			new CompoundQueryResult( [], $this->query, [], $this->store )
		);
	}

}
