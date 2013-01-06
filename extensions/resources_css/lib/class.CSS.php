<?php



	require_once EXTENSIONS.'/resources/lib/class.Resources_Factory.php';

	require_once('class.Resources_FileCSS.php');

	require_once('class.CSS_Res.php');
	require_once('class.Pages_FileCSS.php');
	require_once('class.Widgets_FileCSS.php');



	/**
	 * Provides easy access to specialized CSS objects.
	 */
	Final Class CSS extends Resources_Factory
	{

		protected static function factory(){
			return 'CSS';
		}

		/**
		 * @return CSS_Res
		 */
		public static function Res(){
			return self::getFromMethod( __METHOD__, self::factory(), self::factory() );
		}

	}
