<?php



	/**
	 * Base class for operations with Resource config.
	 */
	Class Resources_FileConfig extends Resources_File
	{

		public function __construct($factory){
			parent::__construct( $factory );

			$this->dir = 'config';
			$this->ext = 'xml';
		}

		public function template($handle){
			$file = EXTENSIONS.'/resources/templates/config.tpl';

			if( !file_exists($file) )
				throw new Exception(__('Config template not found in')." <code>$file</code>.");

			$data = file_get_contents( $file );

			return sprintf( $data, $handle, $handle );
		}

		/**
		 * Get setting.
		 *
		 * @param string  $handle    - handle
		 * @param string  $xpath     - xPath
		 * @param string  $path      - path to Resource (WORKSPACE/resources/tab-handle)
		 * @param boolean $as_string - return as plain string
		 *
		 * @return string|SimpleXMLElement[]
		 */
		public function get($handle, $xpath, $path = null, $as_string = false){
			$path_res = $this->factory( 'Res' )->pathRes( $handle, $path );
			$file = $this->pathFile( $handle, $path_res );

			return XML::get( $file, $xpath, $as_string );
		}

		/**
		 * Set setting.
		 *
		 * @param string $handle - handle
		 * @param string $value  - return as plain string
		 * @param string $xpath  - xPath
		 * @param string $path   - path to Resource (WORKSPACE/resources/tab-handle)
		 *
		 * @return bool
		 */
		public function set($handle, $value, $xpath, $path = null){
			// ain't working yet
//			$path_res = $this->factory( 'Res' )->pathRes( $handle, $path );
//			$file = $this->pathFile( $handle, $path_res );
//
//			return XML::set( $file, $xpath, $value );
		}



		// Related Resources operations

		/**
		 * Get a list of related resources.
		 *
		 * @param string $handle - handle of resource
		 *
		 * @return array
		 */
		public function listRelatedResources($handle){
			$path = $this->factory( 'Res' )->pathRes( $handle );
			$path = $this->pathDir($path);

			$result = array();

			$dir = General::listStructure( $path, '/resources.[\\w-]+.xml/', false, 'asc' );
			if( is_array( $dir['filelist'] ) )
				$result = $this->getResources($dir['filelist']);

			return $result;
		}

		private function getResources($pool){
			$result = array();

			foreach( $pool as $file ){
				$handle = $this->getHandleFromFilename(basename($file));
				$resources = XML::get( $file, '/data/resource ' );

				foreach( $resources as $sxmlitem ){
					$result[$handle][] = (string) $sxmlitem[0];
				}
			}

			return $result;
		}

		private function getHandleFromFilename($filename){
			return preg_replace(array('/^resources./i', '/.xml$/i'), '', $filename);
		}
	}
