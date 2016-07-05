<?php

namespace SCQ\Tests;

use SCQQueryProcessor;

/**
 * @covers SCQQueryProcessor
 * @ingroup SemanticCompoundQueries
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */

class SCQQueryProcessorTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod( &$object, $methodName, array $parameters = array() ) {
		$reflection = new \ReflectionClass( get_class( $object ) );
		$method = $reflection->getMethod( $methodName );
		$method->setAccessible( true );

		return $method->invokeArgs( $object, $parameters );
	}

	public function testSeparateParams() {
		$params = [
			'[[Something in Square Brackets]]',
			'   [[More in Square Brackets with whitespace]]   ',
			'[[Square Brackets]]=with equals',
			'not in=square brackets',
			'   trim   =this',
			'don\'t trim=   this   ',
			'TOLOWERCASE=this',
		];
		$expected = [
			[
				'[[Something in Square Brackets]]',
				'   [[More in Square Brackets with whitespace]]   ',
				'[[Square Brackets]]=with equals',
			],
			[
				'not in' => 'square brackets',
				'trim'=> 'this',
				'don\'t trim' => '   this   ',
				'tolowercase' => 'this',
			]
		];

		$this->assertEquals( $expected, SCQQueryProcessor::separateParams( $params ) );
	}

	public function testGetSubParams() {
		$param = 'split; trimsplit ;split;[don\'t;split];split;[[[don\'t;split]]];split;[[don\'t;split';
		$expected = [
			'split',
			'trimsplit',
			'split',
			'[don\'t;split]',
			'split',
			'[[[don\'t;split]]]',
			'split',
			'[[don\'t;split',
		];

		$actual = $this->invokeMethod( new SCQQueryProcessor(), 'getSubParams', array( $param ) );
		$this->assertEquals( $expected, $actual );
	}
}
