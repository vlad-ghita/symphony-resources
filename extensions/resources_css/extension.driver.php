<?php



	require_once 'lib/class.CSS.php';



	Class Extension_Resources_CSS extends Extension
	{

		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			// Initialize
			$this->initialize();

			// CSS
			CSS::Res()->create();

			// Pages
			if( Resources::regExists( 'pages' ) ){
				$path = Resources::pathBuild( 'pages' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Pages::getObj( 'FileCSS' )->create( $handle, "$path/$handle" );
					}
			}

			// Widgets
			if( Resources::regExists( 'widgets' ) ){
				$path = Resources::pathBuild( 'widgets' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Widgets::getObj( 'FileCSS' )->create( $handle, "$path/$handle" );
					}
			}

			return true;
		}

		public function uninstall(){
			// Initialize
			$this->initialize();

			// CSS
			CSS::Res()->delete();

			// Pages
			if( Resources::regExists( 'pages' ) ){
				$path = Resources::pathBuild( 'pages' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Pages::getObj( 'FileCSS' )->delete( "$path/$handle" );
					}
			}

			// Widgets
			if( Resources::regExists( 'widgets' ) ){
				$path = Resources::pathBuild( 'widgets' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Widgets::getObj( 'FileCSS' )->delete( "$path/$handle" );
					}
			}

			return true;
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Delegates  */
		/*------------------------------------------------------------------------------------------------*/
		public function getSubscribedDelegates(){
			return array_merge(
				$this->delegatesInitialization()
			);
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Initialization  */
		/*------------------------------------------------------------------------------------------------*/

		private function delegatesInitialization(){
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendInitialised',
					'callback' => 'dFrontendInitialised'
				),
				array(
					'page' => '/backend/',
					'delegate' => 'InitaliseAdminPageHead',
					'callback' => 'dInitialiseAdminPageHead'
				)
			);
		}

		public function dFrontendInitialised(){
			$this->initialize();
		}

		public function dInitialiseAdminPageHead(){
			$this->initialize();
		}

		private function initialize(){
			$path = new Path(WORKSPACE, 'css', '', true);

			// register this Resource
			Resources::reg( 'css', $path );

			// Pages integration
			Resources::reg( 'pages_css', $path, 'css' );
			Resources::fileAttach('pages', 'css');
			Resources::relAttach( 'pages', 'pages_css' );

			// Widgets integration
			Resources::reg( 'widgets_css', $path, 'css' );
			Resources::fileAttach('widgets', 'css');
			Resources::relAttach( 'widgets', 'widgets_css' );
		}

	}
