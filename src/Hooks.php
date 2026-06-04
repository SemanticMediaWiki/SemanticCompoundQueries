<?php

namespace SCQ;

use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Parser\Parser;

/**
 * @license GPL-2.0-or-later
 */
class Hooks implements ParserFirstCallInitHook {

	/**
	 * Registers the #compound_query parser function.
	 *
	 * @since 4.0.0
	 *
	 * @param Parser $parser
	 */
	public function onParserFirstCallInit( $parser ): void {
		$parser->setFunctionHook( 'compound_query', [ CompoundQueryProcessor::class, 'doCompoundQuery' ] );
	}

}
