<?php



	/**
	 * Provides access to Configuration file of a Resource.
	 */
	Class XML
	{

		/**
		 * Get a value.
		 *
		 * @static
		 *
		 * @param string  $file      - the file
		 * @param string  $xpath     - xPath
		 * @param boolean $as_string - return as plain string
		 *
		 * @return string|array of SimpleXMLElement
		 */
		public static function get($file, $xpath, $as_string = false){
			if( !file_exists($file) || !is_readable($file) ) return false;

			if( !$data = file_get_contents( $file ) ) return false;

			$config = new SimpleXMLElement($data);

			$result = $config->xpath( $xpath );

			if( $as_string === false ) return $result;

			$result = $result[0];

			return (string) $result;
		}

		/**
		 * Set a value.
		 *
		 * @static
		 *
		 * @param string  $file  - the file
		 * @param string  $xpath - xPath
		 * @param string  $value - new value
		 *
		 * @return string|array of SimpleXMLElement
		 */
		public static function set($file, $xpath, $value){
			if( !file_exists($file) || !is_readable($file) ) return false;

			if( !$data = file_get_contents( $file ) ) return false;

			$config = new SimpleXMLElement($data);

			$result = $config->xpath( $xpath );

			foreach( $result as $item ) {
				$item[0] = $value;
			}

			return true;
		}

	}
