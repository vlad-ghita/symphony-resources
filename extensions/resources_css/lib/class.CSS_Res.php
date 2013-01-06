<?php



	/**
	 * CSS class for CRUD operations.
	 */
	Class CSS_Res extends Resources_Res
	{

		/**
		 * Creates a CSS.
		 *
		 * @param string $path - path
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function create($path = null){
			$path = $this->path( $path );

			General::realiseDirectory($path);

			return true;
		}

		/**
		 * Deletes a CSS.
		 *
		 * @param string $path - path
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function delete($path = null){
			$path = $this->path( $path );

			return General::deleteDirectory( $path );
		}

	}
