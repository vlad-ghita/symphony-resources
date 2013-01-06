<?php



	/**
	 * Manager for current type Resources
	 */
	Class Resources_ManCur extends Resources_Man
	{

		/**
		 * Holds default handle.
		 *
		 * @var string
		 */
		protected $def = 'default';

		/**
		 * Holds current handle.
		 *
		 * @var string
		 */
		protected $cur;



		public function __construct($factory){
			parent::__construct($factory);

			$this->cur = $this->def;
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Getters & Setters */
		/*------------------------------------------------------------------------------------------------*/

		public function getDef(){
			return $this->def;
		}

		public function getCur(){
			return $this->cur;
		}

		/**
		 * Set current Resource
		 *
		 * @param string $handle
		 * @param string $path
		 * @param bool   $check_exists - true to check for Resource existence
		 *
		 * @return bool
		 */
		public function setCur($handle, $path = null, $check_exists = true){
			if( $check_exists === true ){
				if( !$this->factory( 'Man' )->exists( $handle, $path ) ) return false;
			}

			return $this->cur = $handle;
		}

	}
