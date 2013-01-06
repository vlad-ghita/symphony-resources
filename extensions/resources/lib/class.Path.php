<?php



	/**
	 * A Path configuration class for storing information about a path.
	 */
	Class Path
	{

		/**
		 * Root path.
		 *
		 * @var string
		 */
		protected $root;

		/**
		 * Base folder.
		 *
		 * @var string
		 */
		protected $base;

		/**
		 * Extra path from $base folder.
		 *
		 * @var string
		 */
		protected $rel;

		/**
		 * When building the path, decides if $root is included or not.
		 *
		 * @var bool
		 */
		protected $full;



		/**
		 * Configuration object for path.
		 *
		 * @param string $root - root folder
		 * @param string $base - base folder name
		 * @param string $rel  - extra path after $base
		 * @param bool   $full - decides if build path includes root
		 */
		public function __construct($root, $base, $rel, $full){
			$this->setRoot( $root );
			$this->setBase( $base );
			$this->setRel( $rel );
			$this->setFull( $full );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Getters & Setters  */
		/*------------------------------------------------------------------------------------------------*/

		public function getRoot(){
			return (string) $this->root;
		}

		public function setRoot($root){
			$this->root = rtrim( $root, '/' );
		}

		public function getBase(){
			return (string) $this->base;
		}

		public function setBase($base){
			$this->base = trim( $base, '/' );
		}

		public function getRel(){
			return (string) $this->rel;
		}

		public function setRel($after){
			$this->rel = trim( $after, '/' );
		}

		public function getFull(){
			return (string) $this->full;
		}

		public function setFull($full){
			$this->full = !!$full;
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Utilities  */
		/*------------------------------------------------------------------------------------------------*/

		/**
		 * Return computed Base path
		 *
		 * @param $full - if it should return full path or not
		 *
		 * @return string
		 */
		public function doBase($full = null){
			$path = $this->base;

			if( !is_bool($full) ){
				$full = $this->full;
			}

			if( $full ){
				$path = $this->root.'/'.$this->rel;
			}

			return $full ? rtrim( $path, '/' ) : trim( $path, '/' );
		}

		/**
		 * Return computed Rel path
		 *
		 * @param $full - if it should return full path or not
		 *
		 * @return string
		 */
		public function doRel($full = null){
			$path = $this->doBase().'/'.$this->rel;

			if( !is_bool($full) ){
				$full = $this->full;
			}

			return $full ? rtrim( $path, '/' ) : trim( $path, '/' );
		}

	}
