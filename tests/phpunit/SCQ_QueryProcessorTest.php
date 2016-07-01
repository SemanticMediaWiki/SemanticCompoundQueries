<?php

/**
 * @covers SCQQueryProcessor
 * @ingroup SemanticCompoundQueries
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */

class SCQQueryProcessorTest extends \PHPUnit_Framework_TestCase
{
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
}
