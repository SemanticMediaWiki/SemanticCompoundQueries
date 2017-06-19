<?php

namespace SCQ\Tests;

use SCQ\CompoundQueryProcessor;

/**
 * @covers \SCQ\CompoundQueryProcessor
 * @group semantic-compound-queries
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */
class CompoundQueryProcessorTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod( &$object, $methodName, array $parameters = [] ) {

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

		$this->assertEquals( $expected, CompoundQueryProcessor::separateParams( $params ) );
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

		$processor = new CompoundQueryProcessor();
		$actual = $this->invokeMethod( $processor, 'getSubParams', [ $param ] );
		$this->assertEquals( $expected, $actual );
	}

}
