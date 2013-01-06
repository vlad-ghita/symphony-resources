<?php



	require_once EXTENSIONS.'/resources/lib/class.Resources_Factory.php';

	require_once('class.Images_Res.php');
	require_once('class.Pages_FileImages.php');
	require_once('class.Widgets_FileImages.php');



	/**
	 * Provides easy access to specialized Images objects.
	 */
	Final Class Images extends Resources_Factory
	{

		protected static function factory(){
			return 'Images';
		}

		/**
		 * @return Images_Res
		 */
		public static function Res(){
			return self::getFromMethod( __METHOD__, self::factory(), self::factory() );
		}

	}
