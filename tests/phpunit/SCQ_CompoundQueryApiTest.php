<?php


use SMW\Tests\Utils\MwApiFactory;

/**
 * @covers SCQCompoundQueryApi
 * @ingroup SemanticCompoundQueries
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */

class SCQCompoundQueryApiTest extends \PHPUnit_Framework_TestCase
{
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
	 * @param array $query
	 * @param array $expected
	 *
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
		// #0 Standard query
		$provider[] = array(
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
		$provider[] = array(
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
		$provider[] = array(
			array(
				'[[Modification date::+!]];limit=3'
			),
			array(
				array(
					'error'=> 'foo',
				)
			)
		);
		return $provider;
	}
}
