<?php

/**
 * Class that holds static functions for handling compound queries.
 * This class inherits from Semantic MediaWiki's SMWQueryProcessor.
 *
 * @ingroup SemanticCompoundQueries
 * 
 * @author Yaron Koren
 */
class SCQQueryProcessor extends SMWQueryProcessor {

	/**
	 * Handler for the #compound_query parser function.
	 * 
	 * @param Parser $parser
	 * 
	 * @return string
	 */
	public static function doCompoundQuery( Parser &$parser ) {
		global $smwgQEnabled, $smwgIQRunningNumber;
		
		if ( $smwgQEnabled ) {
			$smwgIQRunningNumber++;
			
			$params = func_get_args();
			array_shift( $params ); // We already know the $parser.
			
			$other_params = array();
			$query_result = null;
			$results = array();
			
			foreach ( $params as $param ) {
				// very primitive heuristic - if the parameter
				// includes a square bracket, then it's a
				// sub-query; otherwise it's a regular parameter
				if ( strpos( $param, '[' ) !== false ) {
					$sub_params = self::getSubParams( $param );
					$next_result = self::getQueryResultFromFunctionParams( $sub_params, SMW_OUTPUT_WIKI );
					
					if ( method_exists( $next_result, 'getResults' ) ) { // SMW 1.5+
						$results = self::mergeSMWQueryResults( $results, $next_result->getResults() );
					} else {
						if ( $query_result == null ) {
							$query_result = new SCQQueryResult( $next_result->getPrintRequests(), new SMWQuery() );
						}
						
						$query_result->addResult( $next_result );
					}
				} else {
					$parts = explode( '=', $param, 2 );
					
					if ( count( $parts ) >= 2 ) {
						$other_params[strtolower( trim( $parts[0] ) )] = $parts[1]; // don't trim here, some params care for " "
					}
				}
			}
			
			// SMW 1.5+
			if ( is_null( $query_result ) ) {
				$query_result = new SCQQueryResult( $next_result->getPrintRequests(), new SMWQuery(), $results, smwfGetStore() );
			}
				
			$result = self::getResultFromQueryResult( $query_result, $other_params, SMW_OUTPUT_WIKI );
		} else {
			wfLoadExtensionMessages( 'SemanticMediaWiki' );
			$result = smwfEncodeMessages( array( wfMsgForContent( 'smw_iq_disabled' ) ) );
		}
		
		return $result;
	}	
	
	/**
	 * An alternative to explode() - that function won't work here,
	 * because we don't want to split the string on all semicolons, just
	 * the ones that aren't contained within square brackets
	 * 
	 * @param string $param
	 * 
	 * @return array
	 */
	protected static function getSubParams( $param ) {
		$sub_params = array();
		$sub_param = '';
		$uncompleted_square_brackets = 0;
		
		for ( $i = 0; $i < strlen( $param ); $i++ ) {
			$c = $param[$i];
			
			if ( ( $c == ';' ) && ( $uncompleted_square_brackets <= 0 ) ) {
				$sub_params[] = trim( $sub_param );
				$sub_param = '';
			} else {
				$sub_param .= $c;
				
				if ( $c == '[' ) {
					$uncompleted_square_brackets++;
				}
				
				elseif ( $c == ']' ) {
					$uncompleted_square_brackets--;
				}
			}
		}
		
		$sub_params[] = trim( $sub_param );
		
		return $sub_params;
	}

	/**
	 * @param $rawparams
	 * @param $outputmode
	 * @param $context
	 * @param $showmode
	 * 
	 * @return SMWQueryResult
	 */
	protected static function getQueryResultFromFunctionParams( $rawparams, $outputmode, $context = SMWQueryProcessor::INLINE_QUERY, $showmode = false ) {
		self::processFunctionParams( $rawparams, $querystring, $params, $printouts, $showmode );
		return self::getQueryResultFromQueryString( $querystring, $params, $printouts, SMW_OUTPUT_WIKI, $context );
	}

	/**
	 * Combine two arrays of SMWWikiPageValue objects into one
	 * 
	 * @param array $result1
	 * @param array $result2
	 * 
	 * @return array
	 */
	protected static function mergeSMWQueryResults( $result1, $result2 ) {
		if ( $result1 == null ) {
			return $result2;
		}
		
		$existing_page_names = array();
		
		foreach ( $result1 as $r1 ) {
			$existing_page_names[] = $r1->getWikiValue();
		}
		
		foreach ( $result2 as $r2 ) {
			$page_name = $r2->getWikiValue();
			
			if ( ! in_array( $page_name, $existing_page_names ) ) {
				$result1[] = $r2;
			}
		}
		
		return $result1;
	}

	/**
	 * @param $querystring
	 * @param array $params
	 * @param $extraprintouts
	 * @param $outputmode
	 * @param $context
	 * 
	 * @return SMWQueryResult
	 */
	protected static function getQueryResultFromQueryString( $querystring, array $params, $extraprintouts, $outputmode, $context = SMWQueryProcessor::INLINE_QUERY ) {
		wfProfileIn( 'SCQQueryProcessor::getQueryResultFromQueryString' );
		
		$query  = self::createQuery( $querystring, $params, $context, null, $extraprintouts );
		$query_result = smwfGetStore()->getQueryResult( $query );
		$display_options = array();
		
		foreach ( $params as $key => $value ) {
			// Special handling for 'icon' field, since it requires conversion of a name to a URL.
			if ( $key == 'icon' ) {
				$icon_title = Title::newFromText( $value );
				$icon_image_page = new ImagePage( $icon_title );
				
				// Method was only added in MW 1.13
				if ( method_exists( 'ImagePage', 'getDisplayedFile' ) ) {
					$icon_url = $icon_image_page->getDisplayedFile()->getURL();
					$display_options['icon'] = $icon_url;
				}
			} else {
				$display_options[$key] = $value;
			}
			
			if ( method_exists( $query_result, 'getResults' ) ) { // SMW 1.5+
				foreach ( $query_result->getResults() as $wiki_page ) {
					$wiki_page->display_options = $display_options;
				}
			} else {
				$query_result->display_options = $display_options;
			}
		}

		wfProfileOut( 'SCQQueryProcessor::getQueryResultFromQueryString' );
		
		return $query_result;
	}
	
	/**
	 * Matches getResultFromQueryResult() from SMWQueryProcessor,
	 * except that formats of type 'debug' and 'count' aren't handled.
	 * 
	 * @param SCQQueryResult $res
	 * @param array $params
	 * @param $outputmode
	 * @param $context
	 * @param string $format
	 * 
	 * @return string
	 */
	protected static function getResultFromQueryResult( SCQQueryResult $res, array $params, $outputmode, $context = SMWQueryProcessor::INLINE_QUERY, $format = '' ) {
		wfProfileIn( 'SCQQueryProcessor::getResultFromQueryResult' );

		$format = self::getResultFormat( $params );
		$printer = self::getResultPrinter( $format, $context, $res );
		$result = $printer->getResult( $res, $params, $outputmode );
		
		wfProfileOut( 'SCQQueryProcessor::getResultFromQueryResult' );
		
		return $result;
	}
	
}