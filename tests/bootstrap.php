<?php

if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	die( 'Not an entry point' );
}

if ( !is_readable( $autoloaderClassPath = __DIR__ . '/../../SemanticMediaWiki/tests/autoloader.php' ) ) {
	die( 'The SemanticMediaWiki test autoloader is not available' );
}

if ( !is_readable( $extensionJson = __DIR__ . '/../extension.json' ) ) {
	die( 'The SemanticCompoundQueries extension.json is not readable' );
}

$extensionInfo = json_decode( file_get_contents( $extensionJson ), true );

print sprintf( "\n%-20s%s\n", "Semantic Compound Queries: ", $extensionInfo['version'] );
