<?php



	/**
	 * Base class for operations with JS file for a Page.
	 */
	Final Class Pages_FileJS extends Resources_File
	{

		public function __construct($factory){
			parent::__construct( $factory );

			$this->dir = 'js';
			$this->ext = 'js';
		}

	}
