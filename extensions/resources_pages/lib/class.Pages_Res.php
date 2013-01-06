<?php



	/**
	 * Pages class for CRUD operations.
	 */
	Class Pages_Res extends Resources_Res
	{

		/**
		 * Renames a Page.
		 *
		 * @param string $new  - new handle
		 * @param Path   $path - path
		 * @param string $old  - old handle
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function rename($new, $path = null, $old){
			$path = $this->path( $path );

			parent::rename( $new, $path, $old );

			$this->renameChildren( $new, $path, $old );

			return true;
		}

		/**
		 * Rename children Pages for given old file handle.
		 *
		 * @param string $new  - new handle
		 * @param string $path - path to Base
		 * @param string $old  - old handle
		 *
		 * @return boolean true on success, false otherwise
		 */
		protected function renameChildren($new, $path, $old){
			$dir = General::listStructure( $path, null, false, 'asc', $path );
			$length = strlen( $old );

			if( is_array( $dir['dirlist'] ) )
				foreach( $dir['dirlist'] as $handle ){
					$handle = basename( $handle );

					if( substr( $handle, 0, $length ) === $old && substr( $handle, $length, 1 ) == '_' ){
						$last_part = substr( $handle, $length );
						$_new = $new.$last_part;
						$_old = $old.$last_part;

						parent::rename( $_new, $path, $_old );
					}
				}

			return true;
		}

	}
