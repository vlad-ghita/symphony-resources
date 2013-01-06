<?php



	require_once 'lib/class.Images.php';



	Class Extension_Resources_Images extends Extension
	{

		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			// Initialize
			$this->initialize();

			// Images
			Images::Res()->create();

			// Pages
			if( Resources::regExists( 'pages' ) ){
				$path = Resources::pathBuild( 'pages' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Pages::getObj( 'FileImages' )->create( $handle, "$path/$handle" );
					}
			}

			// Widgets
			if( Resources::regExists( 'widgets' ) ){
				$path = Resources::pathBuild( 'widgets' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Widgets::getObj( 'FileImages' )->create( $handle, "$path/$handle" );
					}
			}

			return true;
		}

		public function uninstall(){
			// Initialize
			$this->initialize();

			// Images
			Images::Res()->delete();

			// Pages
			if( Resources::regExists( 'pages' ) ){
				$path = Resources::pathBuild( 'pages' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Pages::getObj( 'FileImages' )->delete( "$path/$handle" );
					}
			}

			// Widgets
			if( Resources::regExists( 'widgets' ) ){
				$path = Resources::pathBuild( 'widgets' );
				$dir = General::listStructure( $path, null, false, 'asc', $path );
				if( is_array( $dir['dirlist'] ) )
					foreach( $dir['dirlist'] as $handle ){
						$handle = basename( $handle );
						Widgets::getObj( 'FileImages' )->delete( "$path/$handle" );
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
			$path = new Path(WORKSPACE, 'images', '', true);

			// register this Resource
			Resources::reg( 'images', $path );

			// Pages integration
			Resources::reg( 'pages_images', $path );
			Resources::fileAttach('pages', 'images');
			Resources::relAttach( 'pages', 'pages_images' );

			// Widgets integration
			Resources::reg( 'widgets_images', $path );
			Resources::fileAttach('widgets', 'images');
			Resources::relAttach( 'widgets', 'widgets_images' );
		}

	}
