<?php
	
	class BaseModel {
		
		protected $viewmodel;
		
		public function __construct() {
			$this->viewmodel = new ViewModel();
		}
	}