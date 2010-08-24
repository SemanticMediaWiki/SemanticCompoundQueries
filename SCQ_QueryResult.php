<?php

/**
 * Subclass of SMWQueryResult - this class was mostly created in order to
 * get around an inconvenient print-request-compatibility check in
 * SMWQueryResult::addRow()
 *
 * @ingroup SemanticCompoundQueries
 * 
 * @author Yaron Koren
 */
class SCQQueryResult extends SMWQueryResult {

	/**
	 * Adds a result, and ensures it's uniqueness by building a
	 * list of pages already in the query result first.
	 * 
	 * @param SMWQueryResult $new_result
	 */
	public function addResult( SMWQueryResult $newResult ) {
		$existingPageNames = array();
		
		while ( $row = $this->getNext() ) {
			if ( $row[0] instanceof SMWResultArray ) {
				$content = $row[0]->getContent();
				$existingPageNames[] = $content[0]->getLongText( SMW_OUTPUT_WIKI );
			}
		}
		
		while ( ( $row = $newResult->getNext() ) !== false ) {
			$row[0]->display_options = $newResult->display_options;
			$content = $row[0]->getContent();
			$pageName = $content[0]->getLongText( SMW_OUTPUT_WIKI );
			
			if ( !in_array( $pageName, $existingPageNames ) ) {
				$this->m_content[] = $row;
			}
		}
		
		reset( $this->m_content );
	}
	
}