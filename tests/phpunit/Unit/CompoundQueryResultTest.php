<?php

namespace SCQ\Tests;

use PHPUnit\Framework\TestCase;
use SCQ\CompoundQueryResult;
use SMW\Query\Query;
use SMW\Store;

/**
 * @covers \SCQ\CompoundQueryResult
 * @group semantic-compound-queries
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */
class CompoundQueryResultTest extends TestCase {

	private $store;
	private $query;

	protected function setUp(): void {
		parent::setUp();

		$this->store = $this->getMockBuilder( Store::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->query = $this->getMockBuilder( Query::class )
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
