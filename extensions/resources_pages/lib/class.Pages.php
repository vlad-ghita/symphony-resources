<?php



	require_once EXTENSIONS.'/resources/lib/class.Resources_Factory.php';

	require_once('class.Pages_Res.php');
	require_once('class.Pages_FileXSL.php');



	/**
	 * Provides access to specialized Pages objects.
	 */
	Final Class Pages extends Resources_Factory
	{

		protected static function factory(){
			return 'Pages';
		}

		/**
		 * @return Resources_FileConfig
		 */
		public static function FileConfig(){
			return self::getFromMethod( __METHOD__, parent::factory(), self::factory() );
		}

		/**
		 * @return Resources_Related
		 */
		public static function Related(){
			return self::getFromMethod( __METHOD__, parent::factory(), self::factory() );
		}

		/**
		 * @return Pages_FileXSL
		 */
		public static function FileXSL(){
			return self::getFromMethod( __METHOD__, self::factory(), self::factory() );
		}

		/**
		 * @return Pages_Res
		 */
		public static function Res(){
			return self::getFromMethod( __METHOD__, self::factory(), self::factory() );
		}

		/**
		 * @return Resources_Man
		 */
		public static function Man(){
			return self::getFromMethod( __METHOD__, parent::factory(), self::factory() );
		}

	}
