<?php



	require_once EXTENSIONS.'/resources/lib/class.Resources_Factory.php';

	require_once('class.JS_Res.php');
	require_once('class.Pages_FileJS.php');
	require_once('class.Widgets_FileJS.php');



	/**
	 * Provides easy access to specialized JS objects.
	 */
	Final Class JS extends Resources_Factory
	{

		protected static function factory(){
			return 'JS';
		}

		/**
		 * @return JS_Res
		 */
		public static function Res(){
			return self::getFromMethod( __METHOD__, self::factory(), self::factory() );
		}

	}
