<?php



	require_once 'lib/class.Pages.php';



	Class Extension_Resources_Pages extends Extension
	{

		private $current_file_path = '';

		/**
		 * Keeps old handle of the Page in case of rename.
		 *
		 * @var string
		 */
		private $old_path_handle = '';

		/**
		 * Keeps directories of Pages that have been deleted.
		 *
		 * @var array
		 */
		private $deleted_pages = array();



		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			// Initialize
			$this->initialize();

			$path = Resources::pathBuild( 'pages' );

			// Pages
			$dir = General::listStructure( $path, null, false );
			if( is_array( $dir['filelist'] ) )
				foreach($dir['filelist'] as $file){
					$handle = basename( $file, '.xsl' );

					Pages::Res()->create( $handle, $path );
					General::writeFile( "$path/$handle/xsl/$handle.xsl", file_get_contents( $file ) );
					General::deleteFile( $file );
				}

			return true;
		}

		public function uninstall(){
			// Pages
			$path = Resources::pathBuild( 'pages' );

			$dir = General::listStructure( $path, null, false, 'asc', $path );
			if( is_array( $dir['dirlist'] ) ){
				foreach($dir['dirlist'] as $handle){
					$handle = basename( $handle );

					if( is_file( "$path/$handle/xsl/$handle.xsl" ) ){
						General::writeFile( PAGES."/$handle.xsl", file_get_contents( "$path/$handle/xsl/$handle.xsl" ) );
						Pages::Res()->delete( $handle, $path );
					}
				}
			}

			return true;
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Delegates  */
		/*------------------------------------------------------------------------------------------------*/
		public function getSubscribedDelegates(){
			return array_merge(
				$this->delegatesInitialization(),
				$this->delegatesRerouteXSL(),
				$this->delegatesPagesIntegration(),
				array(
					array(
						'page' => '/frontend/',
						'delegate' => 'FrontendPageResolved',
						'callback' => 'dFrontendPageResolved'
					)
				)
			);
		}



		public function dFrontendPageResolved($context){
			$this->storeFilePath( $context );
			$this->attachDatasources( $context );
			$this->attachEvents( $context );
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
			// register this Resource
			Resources::reg( 'pages' );

			// register Structures
			Resources::fileAttach( 'pages', 'config' );
			Resources::fileAttach( 'pages', 'xsl' );

			// register paths to Stuctures
			Resources::relAttach( 'pages', 'pages_config' );
			Resources::relAttach( 'pages', 'pages_xsl' );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Reroute XSL */
		/*------------------------------------------------------------------------------------------------*/

		private function delegatesRerouteXSL(){
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendOutputPreGenerate',
					'callback' => 'dFrontendOutputPreGenerate'
				)
			);
		}

		private function storeFilePath($context){
			// store current page path to use later
			$this->current_file_path = PageManager::createFilePath( $context['page_data']['path'], $context['page_data']['handle'] );

			/**
			 * File path stored. Notify members.
			 *
			 * @delegate Pages_PostFilePathStore
			 *
			 * @param string $file_path
			 *  Page file path.
			 */
			Symphony::ExtensionManager()->notifyMembers( 'Pages_PostFilePathStore', '/extensions/resources_pages/', array(
				'page' => $this->current_file_path
			) );

		}

		public function dFrontendOutputPreGenerate($context){
			// set current context
			Resources::contextSetCurrent();

			// reroute XSL source to correct path
			$path = Resources::pathBuild( 'pages', false );
			$page = $this->current_file_path;

			$old = "/workspace/pages/$page";
			$new = "/workspace/$path/$page/xsl/$page";

			$context['xsl'] = str_replace( $old, $new, $context['xsl'] );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Pages integration  */
		/*------------------------------------------------------------------------------------------------*/

		private function delegatesPagesIntegration(){
			return array(
				array(
					'page' => '/blueprints/pages/',
					'delegate' => 'PagePostCreate',
					'callback' => 'dPagePostCreate'
				),
				array(
					'page' => '/blueprints/pages/',
					'delegate' => 'PagePreEdit',
					'callback' => 'dPagePreEdit'
				),
				array(
					'page' => '/blueprints/pages/',
					'delegate' => 'PagePostEdit',
					'callback' => 'dPagePostEdit'
				),
				array(
					'page' => '/blueprints/pages/',
					'delegate' => 'PagePreDelete',
					'callback' => 'dPagePreDelete'
				),
				array(
					'page' => '/blueprints/pages/',
					'delegate' => 'PagePostDelete',
					'callback' => 'dPagePostDelete'
				)
			);
		}

		public function dPagePostCreate($context){
			// create structure
			$page = PageManager::resolvePagePath( $context['page_id'] );
			$page = PageManager::createFilePath( $page, '' );

			Pages::Res()->create( $page );

			$this->removeFiles();
		}

		public function dPagePreEdit(){
			// store old handle for reference later in dPagePostEdit
			$callback = Administration::instance()->getPageCallback();
			$path = PageManager::resolvePagePath( $callback['context'][1] );
			$this->old_path_handle = PageManager::createFilePath( $path, '' );
		}

		public function dPagePostEdit($context){
			$old = $this->old_path_handle;
			$new = PageManager::resolvePagePath( $context['page_id'] );
			$new = PageManager::createFilePath( $new, '' );

			// Page rename
			if( $old !== $new )
				Pages::Res()->rename( $new, null, $old );

			$this->removeFiles();
		}

		public function dPagePreDelete($context){
			// Mark deleted Pages for deletion in dPagePostDelete
			$pages = $context['page_ids'];
			if( !is_array( $pages ) ) $pages = array($pages);

			foreach($pages as $page_id){
				$page = PageManager::resolvePagePath( $page_id );
				$this->deleted_pages[] = PageManager::createFilePath( $page, '' );
			}
		}

		public function dPagePostDelete(){
			// Delete marked Pages
			foreach($this->deleted_pages as $page)
				Pages::Res()->delete( $page );
		}

		/**
		 * Removes unncesessary fles from /workspace/pages
		 */
		private function removeFiles(){
			$dir = General::listStructure( PAGES, null, false );

			if( is_array( $dir['filelist'] ) )
				foreach($dir['filelist'] as $file)
					General::deleteFile( $file );
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Attach Resources  */
		/*------------------------------------------------------------------------------------------------*/

		private function attachDatasources(&$context){
			$dss = $context['page_data']['data_sources'];

			$dss = array_filter( explode( ',', $dss ) );

			$dss_plus = Pages::Related()->get( $this->current_file_path, 'datasources' );
			$dss_plus = array_map( function ($str){
				return (string) $str;
			}, $dss_plus );

			$dss = array_merge( $dss, $dss_plus );
			$dss = array_unique( $dss );
			$dss = implode( ',', $dss );

			$context['page_data']['data_sources'] = $dss;
		}

		private function attachEvents(&$context){
			$events = $context['page_data']['events'];

			$events = array_filter( explode( ',', $events ) );

			$events_plus = Pages::Related()->get( $this->current_file_path, 'events' );
			$events_plus = array_map( function ($str){
				return (string) $str;
			}, $events_plus );

			$events = array_merge( $events, $events_plus );
			$events = array_unique( $events );
			$events = implode( ',', $events );

			$context['page_data']['events'] = $events;
		}

	}
