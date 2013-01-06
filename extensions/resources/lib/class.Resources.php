<?php



	require_once('class.Path.php');
	require_once('class.Resources_Factory.php');
	require_once('class.XML.php');



	/**
	 * Management of Resources as  whole. You know, anything that couldn't fit somewhere else is stuffed here.
	 */
	Final Class Resources
	{

		/**
		 * Registered Resources
		 *
		 * @var array
		 */
		private static $registered = array();

		/**
		 * Keeps track of Resources residing in other Resources
		 *
		 * @var array
		 */
		private static $relations = array();

		/**
		 * Keeps track of Files inside Resources.
		 *
		 * @var array
		 */
		private static $files = array();



		/*------------------------------------------------------------------------------------------------*/
		/*  Registration  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Register a Resource in system.
		 *
		 * It requires the resource ID (how the resource will be indentified),
		 * the Path to the resource and the type of the resource.
		 *
		 * @param string $id      - the handle for the resource
		 * @param Path   $path    - path
		 * @param string $type    - resource type
		 * @param bool   $current - sets a Current Context
		 *
		 * @todo refaction registration to allow multiple options ($type, $current etc)
		 */
		public static function reg($id, $path = null, $type = null, $current = false){
			$id = strtolower( $id );

			if( !self::regExists( $id ) ){
				if( $path === null )
					$path = new Path(WORKSPACE, $id, '', true);

				self::$registered[$id]['path']['def'] = clone $path;
				self::$registered[$id]['path']['cur'] = clone $path;
				self::$registered[$id]['type'] = $type === null ? $id : strtolower( $type );
				self::$registered[$id]['current'] = is_bool($current) ? $current : false;
			}
		}

		/**
		 * Check if Resource is registered in system.
		 *
		 * @param $id
		 *
		 * @return bool
		 */
		public static function regExists($id){
			return in_array( $id, self::regList() );
		}

		/**
		 * Grab a list of registered Resource types.
		 *
		 * @return array
		 */
		public static function regList(){
			return array_keys( self::$registered );
		}

		/**
		 * Get registered resources by type.
		 *
		 * @param $type - target type
		 *
		 * @return array - an array of resources IDs
		 */
		public static function regListByType($type){
			$type = strtolower( $type );

			$result = array();

			foreach( self::$registered as $id => $data )
				if( $data['type'] == $type )
					$result[] = $id;

			return $result;
		}

		/**
		 * Get registered resources by current state.
		 *
		 * @return array - an array of resources IDs
		 */
		public static function regListByCurrent(){
			$result = array();

			foreach( self::$registered as $id => $data )
				if( $data['current'] === true )
					$result[] = $id;

			return $result;
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Path access  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Set Path for a resource.
		 *
		 * @param string $id
		 * @param string $which
		 * @param Path   $path
		 *
		 * @return Path
		 */
		public static function pathSet($id, $which = 'cur', Path $path){
			$id = strtolower( $id );
			$which = strtolower( $which );

			return self::$registered[$id]['path'][$which] = clone $path;
		}

		/**
		 * Get Path for a resource.
		 *
		 * @param        $id
		 * @param string $which
		 *
		 * @return Path
		 */
		public static function pathGet($id, $which = 'cur'){
			$id = strtolower( $id );
			$which = strtolower( $which );

			return self::$registered[$id]['path'][$which];
		}

		/**
		 * Build the path to a Resource.
		 *
		 * @param string|array $breadcrumbs - what's the resource?
		 * @param bool         $full        - return full path or relative
		 * @param string       $exclude     - path from this point onward will be omitted from result
		 * @param bool         $with_self   - if self should be included or not
		 *
		 * @return string
		 */
		public static function pathBuild($breadcrumbs, $full = true, $exclude = null, $with_self = true){
			if( !is_array( $breadcrumbs ) ) $breadcrumbs = array($breadcrumbs);

			$exclude = strtolower( $exclude );
			$breadcrumbs = array_map( 'strtolower', $breadcrumbs );

			$result = '';

			// compute path from breadcrumbs
			while( $res = array_pop( $breadcrumbs ) ){
				$last = $res;

				if( !$with_self && $result == '' ) continue;
				if( $res === $exclude ) return rtrim( $result, '/' );

				$path = self::pathGet( $res, 'cur' );
				$pfull = $path->getFull();
				$path->setFull( false );
				$result = $path->doRel().'/'.$result;
				$path->setFull( $pfull );
			}

			// compute path from relations
			while( $res = self::$relations[$last] ){
				$last = $res;

				if( $res === $exclude ) return rtrim( $result, '/' );

				$path = self::pathGet( $res, 'cur' );
				$pfull = $path->getFull();
				$path->setFull( false );
				$result = $path->doRel().'/'.$result;
				$path->setFull( $pfull );
			}

			if( $full ){
				$result = self::pathGet( $last, 'cur' )->getRoot().'/'.$result;
			}

			return rtrim( $result, '/' );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Relations between physical locations of resources. This works with IDs of Resources  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Resources can be aware of other resources residing in them. $parent is aware about $child.
		 *
		 * @param $parent - handle of resource (eg: 'sites')
		 * @param $child  - handle of resource (eg: 'css'). In case of `css` in `pages` => `pages_css`
		 */
		public static function relAttach($parent, $child){
			$parent = strtolower( $parent );
			$child = strtolower( $child );

			self::$relations[$child] = $parent;
		}

		/**
		 * Remove location info for a Resource.
		 *
		 * @param $child
		 */
		public static function relDetach($child){
			$child = strtolower( $child );

			unset(self::$relations[$child]);
		}

		/**
		 * Get an array of all parent Resources for this Resource.
		 *
		 * @param $child
		 */
		public static function relParents($child){

		}

		/**
		 * Get parent Resource for $child.
		 *
		 * @param $child
		 *
		 * @return mixed - Resource ID if it is found, null otherwise
		 */
		public static function relParent($child){
			$child = strtolower($child);

			if( !array_key_exists($child, self::$relations) ) return null;

			return self::$relations[$child];
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Resources unique ID utilities  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Make a unique ID for this resource ID.
		 * NOTICE: Sensible to Resources Context
		 *
		 * @param $resource_id - a Resource ID
		 *
		 * @return string resulting uniqueID
		 */
		public static function instMakeID($resource_id){
			$result = '';

			$res = $resource_id;

			do{
				$path = self::pathGet( $res, 'cur' );
				$result = $res.'/'.$path->getRel().'/'.$result;

				$res = self::relParent($res);
			}
			while($res !== null);

			return trim($result, '/');
		}

		/**
		 * Identifies Resource instances in a unique ID. It should reverse @see Resources::instMakeID()
		 *
		 * @param $uniqueID - it can be absolute or relative from /workspace
		 *
		 * @return array config with found resources
		 *
		 * e.g. $uniqueID == 'pages/home/pages_css/foo'
		 *
		 * $config = array(
		 *      array(
		 *          ['id'] => 'pages'
		 *          ['handle'] => 'home'
		 *      ),
		 *      array(
		 *          ['id'] => 'pages_css'
		 *          ['handle'] => 'foo'
		 *      )
		 *  )
		 */
		public static function instParseID($uniqueID){
			$result = array();

			$pieces = explode('/', trim($uniqueID, '/'));

			$i = -1;

			foreach( $pieces as $piece ){

				// resource
				if( self::regExists($piece) ){
					$i++;
					$result[$i]['id'] = $piece;
				}

				// resource instance
				else{
					$result[$i]['handle'] = $piece;
				}

			}

			return $result;
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Context utilities  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * This will set the current context for all Resources.
		 */
		public static function contextSetCurrent(){
			foreach(self::regListByCurrent() as $id){
				$current = Resources_Factory::get('ManCur', $id, $id)->getCur();

				Resources::pathGet( $id )->setRel( $current );
			}
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Relations between Resources and contained files  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Create a File relation between $parent & $child
		 *
		 * @param $parent
		 * @param $child
		 */
		public static function fileAttach($parent, $child){
			$parent = strtolower( $parent );
			$child = strtolower( $child );

			self::$files[$parent][$child] = $child;
		}

		/**
		 * Remove File relation between $parent and $child
		 *
		 * @param $parent
		 * @param $child
		 */
		public static function fileDetach($parent, $child){
			$parent = strtolower( $parent );
			$child = strtolower( $child );

			unset(self::$files[$parent][$child]);
		}

		/**
		 * Grab a list of Files that are managed by a resource
		 *
		 * @param $id
		 *
		 * @return array
		 */
		public static function fileList($id){
			$id = strtolower( $id );

			return isset(self::$files[$id]) ? self::$files[$id] : array();
		}

	}
