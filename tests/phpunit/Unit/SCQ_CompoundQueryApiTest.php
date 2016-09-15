<?php

namespace SCQ\Tests;

use SMW\Tests\Utils\MwApiFactory;
use SCQCompoundQueryApi;

/**
 * @covers SCQCompoundQueryApi
 * @group SemanticCompoundQueries
 * @ingroup SemanticCompoundQueries
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */

class SCQCompoundQueryApiTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var MwApiFactory
	 */
	private $apiFactory;

	protected function setUp() {
		parent::setUp();
		$this->apiFactory = new MwApiFactory();
	}

	public function testCanConstruct() {
		$instance = new SCQCompoundQueryApi(
			$this->apiFactory->newApiMain( array( 'query' => 'Foo' ) ),
			'compoundquery'
		);
		$this->assertInstanceOf(
			'SCQCompoundQueryApi',
			$instance
		);
	}

	/**
	 * @dataProvider sampleQueryProvider
	 */
	public function testExecute( array $query, array $expected ) {
		$results = $this->apiFactory->doApiRequest( array(
			'action' => 'compoundquery',
			'query' => implode( '|', $query )
		) );
		$this->assertInternalType( 'array', $results );
		// If their is no printrequests array we expect an error array
		if ( isset( $results['query']['printrequests'] ) ) {
			$this->assertEquals( $expected, $results['query']['printrequests'] );
		} else {
			$this->assertArrayHasKey( 'error', $results );
		}
	}

	public function sampleQueryProvider() {
		$provider['Standard query'] = array(
			array(
				'[[Modification date::+]];?Modification date;limit=10',
			),
			array(
				array(
					'label'=> '',
					'typeid' => '_wpg',
					'mode' => 2,
					'format' => false
				),
				array(
					'label'=> 'Modification date',
					'typeid' => '_dat',
					'mode' => 1,
					'format' => ''
				)
			)
		);
		$provider['Compound query'] = array(
			array(
				'[[Modification date::+]];?Modification date;limit=10',
				'[[Modification date::+]];?Modification date'
			),
			array(
				array(
					'label'=> '',
					'typeid' => '_wpg',
					'mode' => 2,
					'format' => false
				),
				array(
					'label'=> 'Modification date',
					'typeid' => '_dat',
					'mode' => 1,
					'format' => ''
				)
			)
		);
		return $provider;
	}
}
