<?php

/**
 * @see https://github.com/SemanticMediaWiki/SemanticCompoundQueries/
 *
 * @defgroup SemanticCompoundQueries SemanticCompoundQueries
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is part of the Semantic Compound Queries extension, it is not a valid entry point.' );
}

if ( defined( 'SCQ_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

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

		/**
		 * In case extension.json is being used, the succeeding steps are
		 * expected to be handled by the ExtensionRegistry aka extension.json
		 * ...
		 *
		 * 	"callback": "SemanticCompoundQueries::initExtension",
		 * 	"ExtensionFunctions": [
		 * 		"SemanticCompoundQueries::onExtensionFunction"
		 * 	],
		 */
		self::initExtension();

		$GLOBALS['wgExtensionFunctions'][] = function() {
			self::onExtensionFunction();
		};
	}

	/**
	 * @since 1.1
	 */
	public static function initExtension() {

		define( 'SCQ_VERSION', '1.1.0-alpha' );

		// Register the extension
		$GLOBALS['wgExtensionCredits']['semantic'][] = [
			'path' => __FILE__,
			'name' => 'Semantic Compound Queries',
			'version' => SCQ_VERSION,
			'author' => [
				'[https://www.semantic-mediawiki.org/ Semantic MediaWiki project]',
				'Yaron Koren'
			],
			'url' => 'https://www.mediawiki.org/wiki/Extension:Semantic_Compound_Queries',
			'descriptionmsg' => 'semanticcompoundqueries-desc',
			'license-name' => 'GPL-2.0+'
		];

		// Register message files
		$GLOBALS['wgMessagesDirs']['SemanticCompoundQueries'] = __DIR__ . '/i18n';
		$GLOBALS['wgExtensionMessagesFiles']['SemanticCompoundQueriesMagic'] = __DIR__ . '/i18n/SemanticCompoundQueries.i18n.magic.php';

		//$GLOBALS['wgAutoloadClasses']['SCQQueryProcessor'] = __DIR__ . '/SCQ_QueryProcessor.php';
		//$GLOBALS['wgAutoloadClasses']['SCQQueryResult'] = __DIR__ . '/SCQ_QueryResult.php';
		//$GLOBALS['wgAutoloadClasses']['SCQCompoundQueryApi'] = __DIR__ . '/SCQ_CompoundQueryApi.php';
	}

	/**
	 * @since 1.1
	 */
	public static function checkRequirements() {

		if ( version_compare( $GLOBALS[ 'wgVersion' ], '1.23', 'lt' ) ) {
			die( '<b>Error:</b> This version of <a href="https://github.com/SemanticMediaWiki/SemanticCompoundQueries/">Semantic Compound Queries</a> is only compatible with MediaWiki 1.23 or above. You need to upgrade MediaWiki first.' );
		}

		if ( !defined( 'SMW_VERSION' ) ) {
			die( '<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticCompoundQueries/">Semantic Compound Queries</a> requires the <a href="https://github.com/SemanticMediaWiki/SemanticMediaWiki/">Semantic MediaWiki</a> extension. Please enable or install the extension first.' );
		}
	}

	/**
	 * @since 1.1
	 */
	public static function onExtensionFunction() {

		// Check requirements after LocalSetting.php has been processed, thid has
		// be done here to ensure SMW is loaded in case
		// wfLoadExtension( 'SemanticMediaWiki' ) is used
		self::checkRequirements();

		// wgAPIModules
		$GLOBALS['wgAPIModules']['compoundquery'] = 'SCQ\Api\CompoundQuery';

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

