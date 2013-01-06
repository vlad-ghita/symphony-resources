<?php



	/**
	 * Base class for operations with CSS file for a Page.
	 */
	Abstract Class Resources_FileCSS extends Resources_File
	{

		public function __construct($factory){
			parent::__construct( $factory );

			$this->dir = 'css';
			$this->ext = 'css';
		}

	}
