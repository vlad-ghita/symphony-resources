<?php



	/**
	 * Base class for Resources. It offers the means to access the correct factory obj.
	 */
	Abstract Class Resources_Base
	{

		/**
		 * Resources accesor object.
		 *
		 * @var Resources_Factory
		 */
		protected $factory;

		public function __construct($factory){
			$this->factory = strtolower($factory);
		}

		/**
		 * Accessor for objects.
		 *
		 * @param $method
		 * @param $args
		 *
		 * @return Resources_Base
		 */
		public function factory($method, $args = null){
			return call_user_func( array($this->factory, $method), $args );
		}

	}
