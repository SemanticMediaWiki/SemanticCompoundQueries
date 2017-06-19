<?php

namespace SCQ;

use SMWQueryProcessor as QueryProcessor;
use SMWQuery as Query;
use Parser;

/**
 * Class that holds static functions for handling compound queries.
 * This class inherits from Semantic MediaWiki's QueryProcessor.
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Yaron Koren
 * @author Peter Grassberger < petertheone@gmail.com >
 */
class CompoundQueryProcessor extends QueryProcessor {

	/**
	 * Comparison helper function, used in sorting results.
	 */
	public static function compareQueryResults( $a, $b ) {

		if ( $a->getSerialization() == $b->getSerialization() ) {
			return 0;
		}

		return ( $a->getSerialization() < $b->getSerialization() ) ? -1 : 1;
	}

	/**
	 * Handler for the #compound_query parser function.
	 *
	 * @param Parser $parser
	 *
	 * @return string
	 */
	public static function doCompoundQuery( Parser &$parser ) {
		global $smwgQEnabled, $smwgIQRunningNumber;

		if ( !$smwgQEnabled ) {
			return smwfEncodeMessages( [ wfMessage( 'smw_iq_disabled' )->inContentLanguage()->text() ] );
		}

		$smwgIQRunningNumber++;

		$params = func_get_args();
		array_shift( $params ); // We already know the $parser.

		list( $queryParams, $otherParams ) = self::separateParams( $params );
		list( $queryResult, $otherParams ) = self::queryAndMergeResults( $queryParams, $otherParams );

		return self::getResultFromQueryResult(
			$queryResult,
			$otherParams,
			SMW_OUTPUT_WIKI
		);
	}

	/**
	 * Separates $queryParams from $otherParams.
	 *
	 * @param $params
	 * @return array
	 */
	public static function separateParams( $params ) {
		$queryParams = [];
		$otherParams = [];

		foreach ( $params as $param ) {
			// Very primitive heuristic - if the parameter
			// includes a square bracket, then it's a
			// sub-query; otherwise it's a regular parameter.
			if ( strpos( $param, '[' ) !== false ) {
				$queryParams[] = $param;
			} else {
				$parts = explode( '=', $param, 2 );

				if ( count( $parts ) >= 2 ) {
					$otherParams[strtolower( trim( $parts[0] ) )] = $parts[1]; // don't trim here, some params care for " "
				}
			}
		}
		return [ $queryParams, $otherParams ];
	}

	/**
	 * Query and merge results of subqueries.
	 *
	 * @param $queryParams
	 * @param $otherParams
	 * @return array
	 */
	public static function queryAndMergeResults( $queryParams, $otherParams ) {
		$results = [];
		$printRequests = [];

		foreach ( $queryParams as $param ) {
			$subQueryParams = self::getSubParams( $param );

			if ( array_key_exists( 'format', $otherParams ) && !array_key_exists( 'format', $subQueryParams ) ) {
				$subQueryParams['format'] = $otherParams['format'];
			}

			$nextResult = self::getQueryResultFromFunctionParams($subQueryParams);

			$results = self::mergeSMWQueryResults( $results, $nextResult->getResults() );
			$printRequests = self::mergeSMWPrintRequests( $printRequests, $nextResult->getPrintRequests() );
		}

		// Sort results so that they'll show up by page name
		if( !isset($otherParams['unsorted']) || !strcmp( $otherParams['unsorted'], 'on' ) ) {
			uasort( $results, [ '\SCQ\CompoundQueryProcessor', 'compareQueryResults' ] );
		}

		$queryResult = new CompoundQueryResult( $printRequests, new Query(), $results, smwfGetStore() );

		if ( version_compare( SMW_VERSION, '1.6.1', '>' ) ) {
			self::addThisPrintout( $printRequests, $otherParams );
			$otherParams = self::getProcessedParams( $otherParams, $printRequests );
		}

		return [ $queryResult, $otherParams ];
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
		$sub_params = [];
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
	 * @param $context
	 * @param $showmode
	 *
	 * @return SMWQueryResult
	 */
	protected static function getQueryResultFromFunctionParams( $rawparams, $context = QueryProcessor::INLINE_QUERY, $showmode = false ) {
		$printouts = [];
		self::processFunctionParams( $rawparams, $querystring, $params, $printouts, $showmode );
		return self::getQueryResultFromQueryString( $querystring, $params, $printouts, $context );
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

		$existing_page_names = [];
		foreach ( $result1 as $r1 ) {
			$existing_page_names[] = $r1->getSerialization();
		}

		foreach ( $result2 as $r2 ) {
			$page_name = $r2->getSerialization();
			if ( ! in_array( $page_name, $existing_page_names ) ) {
				$result1[] = $r2;
			}
		}

		return $result1;
	}

	protected static function mergeSMWPrintRequests( $printRequests1, $printRequests2 ) {
		$existingPrintoutLabels = [];
		foreach ( $printRequests1 as $p1 ) {
			$existingPrintoutLabels[] = $p1->getLabel();
		}

		foreach ( $printRequests2 as $p2 ) {
			$label = $p2->getLabel();
			if ( ! in_array( $label, $existingPrintoutLabels ) ) {
				$printRequests1[] = $p2;
			}
		}
		return $printRequests1;
	}

	/**
	 * @param $querystring
	 * @param array $params
	 * @param $extraPrintouts
	 * @param $outputMode
	 * @param $context
	 *
	 * @return SMWQueryResult
	 */
	protected static function getQueryResultFromQueryString( $querystring, array $params, $extraPrintouts, $context = QueryProcessor::INLINE_QUERY ) {

		if ( version_compare( SMW_VERSION, '1.6.1', '>' ) ) {
			QueryProcessor::addThisPrintout( $extraPrintouts, $params );
			$params = self::getProcessedParams( $params, $extraPrintouts, false );
		}

		$query = self::createQuery( $querystring, $params, $context, null, $extraPrintouts );
		$queryResult = smwfGetStore()->getQueryResult( $query );

		$parameters = [];

		if ( version_compare( SMW_VERSION, '1.7.2', '>' ) ) {
			foreach ( $params as $param ) {
				$parameters[$param->getName()] = $param->getValue();
			}
		}
		else {
			$parameters = $params;
		}

		foreach ( $queryResult->getResults() as $wikiPage ) {
			$wikiPage->display_options = $parameters;
		}

		return $queryResult;
	}

	/**
	 * Matches getResultFromQueryResult() from SMWQueryProcessor,
	 * except that formats of type 'debug' and 'count' aren't handled.
	 *
	 * @param CompoundQueryResult $res
	 * @param array $params These need to be the result of a list fed to getProcessedParams as of SMW 1.6.2
	 * @param $outputmode
	 * @param $context
	 * @param string $format
	 *
	 * @return string
	 */
	protected static function getResultFromQueryResult( CompoundQueryResult $res, array $params, $outputmode, $context = QueryProcessor::INLINE_QUERY, $format = '' ) {

		if ( version_compare( SMW_VERSION, '1.6.1', '>' ) ) {
			$format = $params['format'];

			if ( version_compare( SMW_VERSION, '1.7.2', '>' ) ) {
				$format = $format->getValue();
			}
		} else {
			$format = self::getResultFormat( $params );
		}

		$printer = self::getResultPrinter( $format, $context );
		$result = $printer->getResult( $res, $params, $outputmode );

		return $result;
	}

}
