<?php



	require_once 'lib/class.Events.php';



	Class Extension_Resources_Events extends Extension
	{

		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			return true;
		}

		public function uninstall(){
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
			Resources::reg( 'events' );
		}

	}
