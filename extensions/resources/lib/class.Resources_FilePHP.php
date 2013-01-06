<?php



	/**
	 * Base class for operations with an PHP file.
	 */
	Class Resources_FilePHP extends Resources_File
	{

		public function __construct($factory){
			parent::__construct( $factory );

			$this->dir = 'php';
			$this->ext = 'php';
		}

	}
