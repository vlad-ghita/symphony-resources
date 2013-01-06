<?php



	require_once 'lib/class.JS.php';



	Class Extension_Resources_JS extends Extension
	{

		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			// Initialize
			$this->initialize();

			// JS
			JS::Res()->create();

			// Pages
			if( Resources::regExists( 'pages' ) ){
				$path = Resources::pathBuild( 'pages' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Pages::getObj( 'FileJS' )->create( $handle, "$path/$handle" );
					}
			}

			// Widgets
			if( Resources::regExists( 'widgets' ) ){
				$path = Resources::pathBuild( 'widgets' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Widgets::getObj( 'FileJS' )->create( $handle, "$path/$handle" );
					}
			}

			return true;
		}

		public function uninstall(){
			// Initialize
			$this->initialize();

			// JS
			JS::Res()->delete();

			// Pages
			if( Resources::regExists( 'pages' ) ){
				$path = Resources::pathBuild( 'pages' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Pages::getObj( 'FileJS' )->delete( "$path/$handle" );
					}
			}

			// Widgets
			if( Resources::regExists( 'widgets' ) ){
				$path = Resources::pathBuild( 'widgets' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Widgets::getObj( 'FileJS' )->delete( "$path/$handle" );
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
			$path = new Path(WORKSPACE, 'js', '', true);

			// register this Resource
			Resources::reg( 'js', $path );

			// Pages integration
			Resources::reg( 'pages_js', $path );
			Resources::fileAttach('pages', 'js');
			Resources::relAttach( 'pages', 'pages_js' );

			// Widgets integration
			Resources::reg( 'widgets_js', $path );
			Resources::fileAttach('widgets', 'js');
			Resources::relAttach( 'widgets', 'widgets_js' );
		}

	}
