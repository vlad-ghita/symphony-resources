<?php



	require_once EXTENSIONS.'/resources/lib/class.Resources_Factory.php';



	/**
	 * Provides access to specialized Widget objects.
	 */
	Final Class Widgets extends Resources_Factory
	{

		protected static function factory(){
			return 'Widgets';
		}

		/**
		 * @return Resources_FileConfig
		 */
		public static function FileConfig(){
			return self::getFromMethod( __METHOD__, parent::factory(), self::factory() );
		}

		/**
		 * @return Resources_Res
		 */
		public static function Res(){
			return self::getFromMethod( __METHOD__, parent::factory(), self::factory() );
		}

		/**
		 * @return Resources_Man
		 */
		public static function Man(){
			return self::getFromMethod( __METHOD__, parent::factory(), self::factory() );
		}

	}
