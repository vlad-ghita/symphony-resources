<?php



	/**
	 */
	Abstract Class Resources_File extends Resources_Base
	{

		/**
		 * Directory name
		 *
		 * @var string
		 */
		protected $dir = '';

		/**
		 * Filename prefix
		 *
		 * @var string
		 */
		protected $pre = '';

		/**
		 * Filename suffix
		 *
		 * @var string
		 */
		protected $suf = '';

		/**
		 * Filename extension
		 *
		 * @var string
		 */
		protected $ext = 'txt';



		/*------------------------------------------------------------------------------------------------*/
		/*  Create  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Creates a File (dir & file)
		 *
		 * @param string $new  - new handle
		 * @param string $path - path to Resource (WORKSPACE/resources/the-handle)
		 * @param string $old  - old handle
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function create($new, $path, $old = null){
			$this->createDir( $path );
			$this->createFile( $new, $path, $old );
		}

		/**
		 * Creates the directory
		 *
		 * @param string $path - path to Resource
		 *
		 * @return bool
		 */
		public function createDir($path){
			$dir_path = $this->pathDir( $path );

			return General::realiseDirectory( $dir_path );
		}

		/**
		 * Creates the file.
		 *
		 * @param string $new  - new handle
		 * @param string $path - path to Resource
		 * @param string $old  - old handle
		 *
		 * @return bool
		 */
		public function createFile($new, $path, $old = null){
			if( $this->ext !== null ){
				$new_file = $this->pathFile( $new, $path );
				$old_file = $old != null ? $this->pathFile( $old, $path ) : false;

				if( !is_file( $new_file ) ){
					if( file_exists( $old_file ) && is_readable( $old_file ) ){
						$new_data = file_get_contents( $old_file );
					}
					else{
						$new_data = $this->template( $new );
					}

					return General::writeFile( $new_file, $new_data );
				}
			}

			return true;
		}

		protected function template($handle = null){
			return '';
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Delete  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Deletes a File
		 *
		 * @param string $handle - resource handle
		 * @param string $path   - path to Resource (WORKSPACE/resources/the-handle)
		 * @param bool   $force  - force the deletion even if there are ther files in it
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function delete($handle, $path, $force = false){
			$this->deleteFile( $handle, $path );
			$this->deleteDir( $path, $force );
		}

		/**
		 * Deletes the directory
		 *
		 * @param string $path   - path to Resource (WORKSPACE/resources/the-handle)
		 * @param bool   $force  - force the deletion even if there are ther files in it
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function deleteDir($path, $force){
			$path = $this->pathDir( $path );

			if( !$force ){
				// make sure there aren't other files using this Dir
				$dir = General::listStructure( $path, array(), false, 'asc', $path );

				if( is_array( $dir['filelist'] ) ) return true;
			}

			return General::deleteDirectory( $path );
		}

		/**
		 * Deletes the file
		 *
		 * @param string $handle - handle of the Resource
		 * @param string $path   - path to Resource
		 *
		 * @return boolean true on success, false otherwise
		 */
		public function deleteFile($handle, $path){
			$file = $this->pathFile( $handle, $path );

			return General::deleteFile( $file );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Paths  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Builds the path to directory.
		 *
		 * @param string $path - to Resource
		 *
		 * @return string - the path to config
		 */
		public function pathDir($path){
			return rtrim( "$path/$this->dir", '/' );
		}

		/**
		 * Builds the path to file.
		 *
		 * @param string $handle
		 * @param string $path - path to Resource
		 *
		 * @return string - the path to config
		 */
		public function pathFile($handle, $path = null){
			$path = $this->pathDir( $path );

			return "$path/$this->pre$handle$this->suf.$this->ext";
		}

	}
