<?php



	/**
	 * Base class for operations with an XSL file.
	 */
	Class Resources_FileXSL extends Resources_File
	{

		public function __construct($factory){
			parent::__construct( $factory );

			$this->dir = 'xsl';
			$this->ext = 'xsl';
		}

	}
