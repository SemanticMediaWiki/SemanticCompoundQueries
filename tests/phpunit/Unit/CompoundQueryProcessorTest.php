<?php

namespace SCQ\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use SCQ\CompoundQueryProcessor;

/**
 * @covers \SCQ\CompoundQueryProcessor
 * @group semantic-compound-queries
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */
class CompoundQueryProcessorTest extends TestCase {

	/**
	 * Call protected/private method of a class.
	 */
	public function invokeMethod( object $object, string $methodName, array $parameters = [] ) {
		$reflection = new ReflectionClass( get_class( $object ) );
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
				'trim' => 'this',
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
