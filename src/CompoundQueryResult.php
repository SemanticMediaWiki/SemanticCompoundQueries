<?php

namespace SCQ;

use SMW\Query\QueryResult;
use SMW\Query\Result\ResultArray;

/**
 * Subclass of SMWQueryResult - this class was mostly created in order to
 * get around an inconvenient print-request-compatibility check in
 * SMWQueryResult::addRow
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Yaron Koren
 */
class CompoundQueryResult extends QueryResult {

	/**
	 * Adds in the pages from a new query result to the existing set of
	 * pages - only pages that weren't in the set already get added.
	 *
	 * @param QueryResult $newResult
	 */
	public function addResult( QueryResult $newResult ) {
		$existingPageNames = [];

		while ( $row = $this->getNext() ) {
			if ( $row[0] instanceof ResultArray ) {
				$content = $row[0]->getContent();
				$existingPageNames[] = $content[0]->getLongText( SMW_OUTPUT_WIKI );
			}
		}

		while ( ( $row = $newResult->getNext() ) !== false ) {
			if ( property_exists( $newResult, 'display_options' ) ) {
				$row[0]->display_options = $newResult->display_options;
			}
			$content = $row[0]->getContent();
			$pageName = $content[0]->getLongText( SMW_OUTPUT_WIKI );

			if ( !in_array( $pageName, $existingPageNames ) ) {
				$this->m_content[] = $row;
			}
		}

		reset( $this->m_content );
	}

}
