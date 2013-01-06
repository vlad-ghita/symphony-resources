<?php



	require_once('lib/class.Resources.php');



	Class Extension_Resources extends Extension
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

		// these are here because class.Resources.php must be included (see top of the file)
		public function dFrontendInitialised(){
		}

		public function dInitialiseAdminPageHead(){
		}

	}
