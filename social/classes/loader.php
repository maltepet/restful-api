<?php
	
	class Loader {
		
		private $controller;
		private $action;
		private $id;
		private $page;
		private $urlvalues;
		
		public function __construct($urlvalues) {
			$this->urlvalues = $urlvalues;
			
			if ($this->urlvalues['controller'] == "") {
				$this->controller = "home";
			} 
			else {
				$this->controller = $this->urlvalues['controller'];
			}
			
			if ($this->urlvalues['action'] == "") {
				$this->action = "index";
			} 
			else {
				$this->action = $this->urlvalues['action'];
			}
			
			if ( $this->controller == 'users' && $this->urlvalues['action'] == '' ) { 
				$this->action = 'profile';
			}
			
			$this->id = $this->urlvalues['id'];
		}
		
		public function CreateController() {
			if ( class_exists($this->controller) ) {
				$parents = class_parents($this->controller);
				
				if ( in_array("BaseController", $parents) ) {
					if ( method_exists($this->controller, $this->action) ) {
						return new $this->controller($this->action, $this->urlvalues);
					}
					
					else {
						print_r($this->urlvalues);
					}
				}
				
				else {
					print_r($this->urlvalues);
				}
			}
			
			else {
				print_r($this->urlvalues);
			}
		}
	}
	
