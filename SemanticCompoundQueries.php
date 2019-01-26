<?php

/**
 * @see https://github.com/SemanticMediaWiki/SemanticCompoundQueries/
 *
 * @defgroup SemanticCompoundQueries SemanticCompoundQueries
 */

SemanticCompoundQueries::load();

/**
 * @codeCoverageIgnore
 */
class SemanticCompoundQueries {

	/**
	 * @since 1.1
	 *
	 * @note It is expected that this function is loaded before LocalSettings.php
	 * to ensure that settings and global functions are available by the time
	 * the extension is activated.
	 */
	public static function load() {

		if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
			include_once __DIR__ . '/vendor/autoload.php';
		}
	}

	/**
	 * @since 1.1
	 */
	public static function initExtension( $credits = [] ) {

		// See https://phabricator.wikimedia.org/T151136
		define( 'SCQ_VERSION', isset( $credits['version'] ) ? $credits['version'] : 'UNKNOWN' );

		// Register message files
		$GLOBALS['wgMessagesDirs']['SemanticCompoundQueries'] = __DIR__ . '/i18n';
		$GLOBALS['wgExtensionMessagesFiles']['SemanticCompoundQueriesMagic'] = __DIR__ . '/i18n/SemanticCompoundQueries.i18n.magic.php';

	}

	/**
	 * @since 1.1
	 */
	public static function onExtensionFunction() {

		if ( !defined( 'SMW_VERSION' ) ) {

			if ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ) {
				die( "\nThe 'Semantic Compound Queries' extension requires 'Semantic MediaWiki' to be installed and enabled.\n" );
			} else {
				die(
					'<b>Error:</b> The <a href="https://www.semantic-mediawiki.org/wiki/Extension:Semantic_Compound_Queries">Semantic Compound Queries</a> ' .
					'extension requires <a href="https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a> to be ' .
					'installed and enabled.<br />'
				);
			}
		}

		// wgAPIModules
		$GLOBALS['wgAPIModules']['compoundquery'] = 'SCQ\Api\CompoundQuery';

		// wgHooks
		$GLOBALS['wgHooks']['ParserFirstCallInit'][] = function( Parser &$parser  ) {
			$parser->setFunctionHook( 'compound_query', [ '\SCQ\CompoundQueryProcessor', 'doCompoundQuery' ] );

			// always return true, in order not to stop MW's hook processing!
			return true;
		};
	}

	/**
	 * @since 1.1
	 *
	 * @return string|null
	 */
	public static function getVersion() {
		return SCQ_VERSION;
	}

}
