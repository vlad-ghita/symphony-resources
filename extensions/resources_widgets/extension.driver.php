<?php



	require_once 'lib/class.Widgets.php';



	Class Extension_Resources_Widgets extends Extension
	{

		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			// Initialize
			$this->initialize();

			// Widgets
			General::realiseDirectory( Resources::pathBuild( 'widgets' ) );

			return true;
		}

		public function uninstall(){
			// Initialize
			$this->initialize();

			// Widgets
			General::deleteDirectory( Resources::pathBuild( 'widgets' ) );

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
			Resources::reg( 'widgets' );
		}

	}
