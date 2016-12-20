<?php
/**
 * Initialization file for the SemanticCompoundQueries extension.
 *
 * @file SemanticCompoundQueries.php
 * @ingroup SemanticCompoundQueries
 *
 * @author Yaron Koren
 */

/**
 * This documentation group collects source-code files belonging to
 * Semantic Compound Queries.
 *
 * @defgroup SemanticCompoundQueries SemanticCompoundQueries
 */

if ( !defined( 'MEDIAWIKI' ) ) die();

define( 'SCQ_VERSION', '1.0.1' );

$GLOBALS['wgExtensionCredits']['semantic'][] = array(
	'path' => __FILE__,
	'name' => 'Semantic Compound Queries',
	'version' => SCQ_VERSION,
	'author' => array( 'Yaron Koren' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Semantic_Compound_Queries',
	'descriptionmsg' => 'semanticcompoundqueries-desc',
	'license-name' => 'GPL-2.0+'
);

$GLOBALS['wgMessagesDirs']['SemanticCompoundQueries'] = __DIR__ . '/i18n';
$GLOBALS['wgExtensionMessagesFiles']['SemanticCompoundQueriesMagic'] = __DIR__ . '/SemanticCompoundQueries.i18n.magic.php';

$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'scqgRegisterParser';

$GLOBALS['wgAutoloadClasses']['SCQQueryProcessor'] = __DIR__ . '/SCQ_QueryProcessor.php';
$GLOBALS['wgAutoloadClasses']['SCQQueryResult'] = __DIR__ . '/SCQ_QueryResult.php';
$GLOBALS['wgAutoloadClasses']['SCQCompoundQueryApi'] = __DIR__ . '/SCQ_CompoundQueryApi.php';

$GLOBALS['wgAPIModules']['compoundquery'] = '\SCQCompoundQueryApi';

function scqgRegisterParser( Parser &$parser ) {
	$parser->setFunctionHook( 'compound_query', array( 'SCQQueryProcessor', 'doCompoundQuery' ) );
	return true; // always return true, in order not to stop MW's hook processing!
}
