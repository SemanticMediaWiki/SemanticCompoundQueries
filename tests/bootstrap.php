<?php

if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	die( 'Not an entry point' );
}

if ( !is_readable( $autoloaderClassPath = __DIR__ . '/../../SemanticMediaWiki/tests/autoloader.php' ) ) {
	die( 'The SemanticMediaWiki test autoloader is not available' );
}

print sprintf( "\n%-20s%s\n", "Semantic Compound Queries: ", SCQ_VERSION );

$autoloader = require $autoloaderClassPath;
$autoloader->addPsr4( 'SCQ\\Tests\\', __DIR__ . '/phpunit/Unit' );
$autoloader->addPsr4( 'SCQ\\Tests\\Integration\\', __DIR__ . '/phpunit/Integration' );
unset( $autoloader );
