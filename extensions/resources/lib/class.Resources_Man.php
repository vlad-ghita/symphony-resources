<?php



	/**
	 * Resources management as a whole.
	 */
	Class Resources_Man extends Resources_Base
	{

		/**
		 * Verify if a Resource exists.
		 *
		 * @param string  $resource - desired resource
		 * @param string  $path     - path config
		 *
		 * @return bool - true if resource exists, false otherwise
		 */
		public function exists($resource, $path = null){
			$resources = $this->listAll( $path );

			return in_array( $resource, $resources );
		}

		/**
		 * Get a list of all Resources.
		 *
		 * @param string $path - path
		 *
		 * @return array
		 */
		public function listAll($path = null){
			if( $path === null ){
				$cur = Resources::pathGet( $this->factory );
				$rel = $cur->getRel();
				$cur->setRel( '' );
				$path = Resources::pathBuild( $this->factory );
				$cur->setRel( $rel );
			}

			$resources = array();

			$dir = General::listStructure( $path, null, false, 'asc', $path );
			if( is_array( $dir['dirlist'] ) )
				foreach( $dir['dirlist'] as $handle )
					$resources[] = basename( $handle );

			return $resources;
		}

	}
