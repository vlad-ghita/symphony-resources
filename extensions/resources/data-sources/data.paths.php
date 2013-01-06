<?php



	require_once(TOOLKIT.'/class.datasource.php');



	Class datasourcepaths extends Datasource
	{

		public function about(){
			return array(
				'name' => 'Paths',
				'author' => array(
					'name' => 'Vlad Ghita',
					'email' => 'vlad.ghita@xandegroup.ro'
				),
				'version' => '1.0'
			);
		}

		public function allowEditorToParse(){
			return false;
		}

		public function execute(&$param_pool = NULL){
			$result = new XMLElement('paths');

			foreach( Resources::regList() as $resource ){
				$bits = explode('_', $resource);

				// this will take care of resources inside resources
				$exclude = $bits[1] ? $bits[0] : null;

				$path = Resources::pathBuild( $resource, false, $exclude );

				$result->appendChild( new XMLElement( $resource, $path ) );
			}

			return $result;
		}
	}
