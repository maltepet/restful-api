<?php
	
	class View {
		
		private $data = array();
		private $render = false;
		
		public function __construct($template) {
			include_once ROOT . '/header.php';
			
			try {
				$file = ROOT . '/templates/' . strtolower($template) . '.php';
				
				if ( file_exists($file) ) {
					$this->render = $file;
				}
				
				else {
					throw new Exception('Template not found');
				}
			}
			
			catch (Exception $e) {
				echo $e->errorMessage();
			}
		}
		
		public function assign($variable, $value) {
			$this->data[$variable] = $value;
		}
		
		public function __destruct() {
			extract($this->data);
			include($this->render);
		}
		
	}