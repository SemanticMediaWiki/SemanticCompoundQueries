<?php

namespace SCQ\Api;

use SMW\MediaWiki\Api\Query;
use SMW\MediaWiki\Api\ApiRequestParameterFormatter;
use SCQ\CompoundQueryProcessor;
use ApiBase;

/**
 * API module to query SMW by providing multiple queries in the ask language.
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Peter Grassberger < petertheone@gmail.com >
 */
class CompoundQuery extends Query {

	/**
	 * @see ApiBase::execute
	 */
	public function execute() {
		$parameterFormatter = new ApiRequestParameterFormatter( $this->extractRequestParams() );
		$parameters = $parameterFormatter->getAskApiParameters();

		list( $queryParams, $otherParams ) = CompoundQueryProcessor::separateParams( $parameters );
		list( $queryResult ) = CompoundQueryProcessor::queryAndMergeResults( $queryParams, $otherParams );

		$outputFormat = 'json';
		if ( $this->getMain()->getPrinter() instanceof \ApiFormatXml ) {
			$outputFormat = 'xml';
		}

		$this->addQueryResult( $queryResult, $outputFormat );
	}

	/**
	 * @codeCoverageIgnore
	 * @see ApiBase::getAllowedParams
	 *
	 * @return array
	 */
	public function getAllowedParams() {
		return [
			'query' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			],
		];
	}

	/**
	 * @codeCoverageIgnore
	 * @see ApiBase::getParamDescription
	 *
	 * @return array
	 */
	public function getParamDescription() {
		return [
			'query' => 'The multiple queries string in ask-language'
		];
	}

	/**
	 * @codeCoverageIgnore
	 * @see ApiBase::getDescription
	 *
	 * @return array
	 */
	public function getDescription() {
		return [
			'API module to query SMW by providing a multiple queries in the ask language.'
		];
	}

	/**
	 * @codeCoverageIgnore
	 * @see ApiBase::getExamples
	 *
	 * @return array
	 */
	protected function getExamples() {
		return [
			'api.php?action=compoundquery&query=' . urlencode( '[[Has city::Vienna]]; ?Has coordinates|[[Has city::Graz]]; ?Has coordinates' ),
			'api.php?action=compoundquery&query=' . urlencode( '|[[Has city::Vienna]]; ?Has coordinates|[[Has city::Graz]]; ?Has coordinates' ),
		];
	}

	/**
	 * @codeCoverageIgnore
	 * @see ApiBase::getVersion
	 *
	 * @return string
	 */
	public function getVersion() {
		return __CLASS__ . '-' . SCQ_VERSION;
	}

}
