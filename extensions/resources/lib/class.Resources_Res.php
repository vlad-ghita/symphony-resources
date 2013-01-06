<?php



	/**
	 * Base class for CRUD operations with Resources.
	 */
	Class Resources_Res extends Resources_Base
	{

		/**
		 * Creates a Resource.
		 *
		 * - Resource folder
		 * - config dir & file
		 *
		 * @param string $new  - new handle
		 * @param string $path - path to Base folder (WORKSPACE/resources)
		 * @param string $old  - old handle
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function create($new, $path = null, $old = null){
			$path = $this->pathRes( $new, $path );

			// Resource
			General::realiseDirectory( $path );

			// create new related Files
			foreach( Resources::fileList( $this->factory ) as $file )
				try{
					$rel = Resources::pathBuild( "{$this->factory}_{$file}", false, $this->factory, false );
					$this->factory( 'File'.$file, $this->factory )->create( $new, "$path/$rel", $old );
				}
				catch( Exception $e ){
					// if there's a
					continue;
				}

			return true;
		}

		/**
		 * Renames a Resource.
		 *
		 * @param string $new  - new handle
		 * @param Path   $path - path to Base folder (WORKSPACE/resources)
		 * @param string $old  - old handle
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function rename($new, $path = null, $old){
			if( $new === $old ) return true;

			$path = $this->path( $path );

			// create new structure
			$new_path = $this->pathRes( $new, $path );
			$old_path = $this->pathRes( $old, $path );

			General::moveDirectory( $old_path, $new_path );

			$this->create( $new, $path, $old );

			// delete old related Files
			foreach( Resources::fileList( $this->factory ) as $file )
				try{
					$rel = Resources::pathBuild( "{$this->factory}_{$file}", false, $this->factory, false );
					$this->factory( 'File'.$file, $this->factory )->deleteFile( $old, "$new_path/$rel" );
				}
				catch( Exception $e ){
					continue;
				}

			return true;
		}

		/**
		 * Deletes a Resource.
		 *
		 * @param string $handle - handle
		 * @param string $path   - path to Base
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function delete($handle, $path = null){
			$path = $this->pathRes( $handle, $path );

			return General::deleteDirectory( $path );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Paths  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Builds the Path.
		 *
		 * @param string $path
		 *
		 * @return string - the path
		 */
		public function path($path = null){
			if( !is_string( $path ) ){
				$path = Resources::pathBuild( $this->factory );
			}

			return $path;
		}

		/**
		 * Builds the path to Resource.
		 *
		 * @param string $handle
		 * @param string $path
		 *
		 * @return string - the path to config
		 */
		public function pathRes($handle, $path = null){
			if( !is_string( $path ) ){
				$path = $this->path( $path );
			}

			return rtrim( "$path/$handle", '/' );
		}
	}
