<?php



	/**
	 * Base class for operations with Images for a Page.
	 */
	Final Class Pages_FileImages extends Resources_File
	{

		public function __construct($factory){
			parent::__construct( $factory );

			$this->dir = 'images';
		}

		public function create($new, $path){
			return $this->createDir($path);
		}

	}
