<?php
	
	abstract class BaseController {
		
		protected $urlvalues;
		protected $action;
		
		public function __construct($action, $urlvalues) {
			$this->urlvalues = $urlvalues;
			$this->action = $action;
		}
		
		public function ExecuteAction() {
			return $this->{$this->action}();
		}
		
		protected function ReturnView($viewmodel, $fullview) {
			$viewloc = 'views/' . strtolower(get_class($this)) . '/' . $this->action . '.php';
			
			if ( $fullview ) {
				require 'views/header.php';
			}
			
			else {
				require $viewloc;
			}
		}
	}