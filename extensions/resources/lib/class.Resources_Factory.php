<?php



	require_once('class.Resources_Base.php');
	require_once('class.Resources_Man.php');
	require_once('class.Resources_ManCur.php');
	require_once('class.Resources_Related.php');
	require_once('class.Resources_Res.php');

	require_once('class.Resources_File.php');
	require_once('class.Resources_FileConfig.php');
	require_once('class.Resources_FilePHP.php');
	require_once('class.Resources_FileXSL.php');



	/**
	 * Provides access to specialized Resource objects.
	 *
	 * DO NOT call this class's methods directly unless you KNOW WHAT YOU'RE DOING.
	 * Please use specialized interfaces from Resources_Factory implementations.
	 *
	 * Thanks !
	 *
	 * The non-forwarding calls to this class's methods will throw loads of errors if
	 * you're a brave heart fellow.
	 */
	Class Resources_Factory
	{

		/**
		 * A cache of Resources objects for easy access.
		 *
		 * @var Resources_Base[]
		 */
		private static $cache = array();



		/**
		 * Catch loosely calls.
		 *
		 * For non-forwarding calls only.
		 *
		 * @param $name
		 * @param $args
		 *
		 * @return Resources_Base
		 */
		public static function __callStatic($name, $args){
			$resource = $args[0] ? $args[0] : self::factory();

			return self::get( $name, $resource, static::factory() );
		}

		/**
		 * Access an object fast.
		 *
		 * e.g. Pages::getObj( 'FileCSS' );
		 *
		 * For non-forwarding calls only.
		 *
		 * @param $object
		 *
		 * @return Resources_Base
		 */
		public static function getObj($object){
			return self::get($object, static::factory(), static::factory());
		}

		/**
		 * Factory method for generating Resources objects.
		 *
		 * @static
		 *
		 * @param $object   - object name
		 * @param $resource - resource where it belongs. Normally this is Resources
		 * @param $factory  - factory Resources object. Eg: Pages, Widgets
		 *
		 * @return Resources_Base
		 */
		public static function get($object, $resource, $factory){
			$object = strtolower($object);
			$resource = strtolower($resource);
			$factory = strtolower($factory);

			if( !isset(self::$cache[$factory][$object]) ){
				self::$cache[$factory][$object] = self::createObject( $resource, $object, $factory );
			}

			return self::$cache[$factory][$object];
		}



		/**
		 * Returns the name of Resources.
		 *
		 * @return string
		 */
		protected static function factory(){
			return 'Resources';
		}

		/**
		 * Get object from method.
		 *
		 * @static
		 *
		 * @param $method
		 * @param $resource
		 * @param $access
		 *
		 * @return Resources_Base
		 */
		protected static function getFromMethod($method, $resource, $access){
			$object = explode('::', $method);
			return self::get( (string) $object[1], $resource, $access );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Internal  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Deals with object creation.
		 *
		 * @static
		 *
		 * @param $resource
		 * @param $object
		 * @param $factory
		 *
		 * @throws Exception
		 *
		 * @return Resources_Base
		 */
		private static function createObject($resource, $object, $factory){
			$class = "{$resource}_{$object}";


			if( !class_exists( $class ) ){
				$resource = self::factory();
				$class = "{$resource}_{$object}";

				// if we've hit an object that's not found in Resources, throw an Exception
				if( !class_exists( $class ) ){
					throw new Exception(sprintf( 'Object "%s" for resource "%s" not found in class "%s".', $object, $resource, $class ));
				}
			}


			/** @var $obj Resources_Base */
			$obj = new $class($factory);

			return $obj;
		}

	}
