<?php

namespace SCQ\Tests\Api;

use PHPUnit\Framework\TestCase;
use SCQ\Api\CompoundQuery;
use SMW\Query\QuerySourceFactory;
use SMW\Tests\Utils\MwApiFactory;

/**
 * @covers \SCQ\Api\CompoundQuery
 * @group semantic-compound-queries
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */
class CompoundQueryTest extends TestCase {

	/**
	 * @var MwApiFactory
	 */
	private $apiFactory;

	protected function setUp(): void {
		parent::setUp();
		$this->apiFactory = new MwApiFactory();
	}

	public function testCanConstruct() {
		$instance = new CompoundQuery(
			$this->apiFactory->newApiMain( [ 'query' => 'Foo' ] ),
			'compoundquery',
			$this->createMock( QuerySourceFactory::class )
		);

		$this->assertInstanceOf(
			CompoundQuery::class,
			$instance
		);
	}

	/**
	 * @dataProvider sampleQueryProvider
	 */
	public function testExecute( array $query, array $expected ) {
		$results = $this->apiFactory->doApiRequest( [
			'action' => 'compoundquery',
			'query' => implode( '|', $query )
		] );

		$this->assertIsArray(
			$results
		);

		// If their is no printrequests array we expect an error array
		if ( isset( $results['query']['printrequests'] ) ) {
			$this->assertEquals( $expected, $results['query']['printrequests'] );
		} else {
			$this->assertArrayHasKey( 'error', $results );
		}
	}

	public function sampleQueryProvider() {
		$provider['Standard query'] = [
			[
				'[[Modification date::+]];?Modification date;limit=10',
			],
			[
				[
					'label' => '',
					'typeid' => '_wpg',
					'mode' => 2,
					'format' => false,
					'key' => '',
					'redi' => ''
				],
				[
					'label' => 'Modification date',
					'typeid' => '_dat',
					'mode' => 1,
					'format' => '',
					'key' => '_MDAT',
					'redi' => ''
				]
			]
		];

		$provider['Compound query'] = [
			[
				'[[Modification date::+]];?Modification date;limit=10',
				'[[Modification date::+]];?Modification date'
			],
			[
				[
					'label' => '',
					'typeid' => '_wpg',
					'mode' => 2,
					'format' => false,
					'key' => '',
					'redi' => ''
				],
				[
					'label' => 'Modification date',
					'typeid' => '_dat',
					'mode' => 1,
					'format' => '',
					'key' => '_MDAT',
					'redi' => ''
				]
			]
		];

		return $provider;
	}

}
