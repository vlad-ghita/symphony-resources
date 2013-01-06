<?php



	/**
	 * Base class for operations with XSL file for a Page.
	 */
	Final Class Pages_FileXSL extends Resources_FileXSL
	{

		public function template($handle){
			return file_get_contents( PageManager::getTemplate( 'blueprints.page' ) );
		}
	}
