<?php



	/**
	 * Deals with Processing of related Resources.
	 */
	Final Class Resources_Related extends Resources_Base
	{

		/**
		 * Keeps track of processed resources to avoid infinite loops.
		 *
		 * @var array
		 */
		private $processed = array();



		/**
		 * Get related Resources.
		 *
		 * @param string $handle    - source Resource
		 * @param string $resources - required Resources
		 * @param bool   $flatten - if resources should be flattened or not
		 *
		 * @return array
		 */
		public function get($handle, $resources, $flatten = true){
			$this->processed = array();

			$this->markProcessed($handle, $this->factory);

			$result = array();

			$this->getFromResources( $this->factory, $handle, $resources, $result );

			if( $flatten ){
				$result = $this->flatten($result, $resources);
			}

			return $result;
		}

		/**
		 * Returns an array of related resources.
		 *
		 * @param $factory
		 * @param $handle
		 * @param $target
		 * @param $result
		 *
		 * @return array
		 */
		private function getFromResources($factory, $handle, $target, &$result){
			$related_resources = Resources_Factory::get( 'fileConfig', 'resources', $factory )->listRelatedResources( $handle, $target );

			foreach( $related_resources as $current => $items ){
				if( $current == $target ){
					$result[$current] = $items;
				}
				else{
					foreach( $items as $rel_item ){

						// avoid infinte loops when Resources are linked recursively to each other
						if( $this->notProcessed( $rel_item, $current ) ){
							$this->markProcessed( $rel_item, $current );

							$temp_result = array();
							$this->getFromResources( $current, $rel_item, $target, $temp_result );

							if( is_array($temp_result) && !empty($temp_result) ){
								$result[$current][$rel_item] = $temp_result;
							}
						}
					}
				}
			}
		}

		private function notProcessed($handle, $resources){
			if( !is_array($this->processed[$resources]) )
				$this->processed[$resources] = array();

			return !in_array( $handle, $this->processed[$resources] );
		}

		private function markProcessed($handle, $resources){
			$this->processed[$resources][] = $handle;
		}

		private function flatten($data, $target){
			$result = array();

			foreach( $data as $key => $items ){
				if( $key == $target ){
					$result = array_merge($result, $items);
				}
				else{
					$result = array_merge($result, $this->flatten($items, $target));
				}
			}

			return $result;
		}

	}
