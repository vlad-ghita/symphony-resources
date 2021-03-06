<?php



	require_once EXTENSIONS.'/resources/lib/class.Resources_Factory.php';



	/**
	 * Provides access to specialized Datasources objects.
	 */
	Final Class Datasources extends Resources_Factory
	{

		protected static function factory(){
			return 'Datasources';
		}

		/**
		 * @return Resources_FileConfig
		 */
		public static function FileConfig(){
			return self::getFromMethod( __METHOD__, parent::factory(), self::factory() );
		}

	}
