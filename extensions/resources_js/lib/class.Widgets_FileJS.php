<?php



	/**
	 * Base class for operations with JS file for a WIdget.
	 */
	Final Class WIdgets_FileJS extends Resources_File
	{

		public function __construct($factory){
			parent::__construct( $factory );

			$this->dir = 'js';
			$this->ext = 'js';
		}

	}
