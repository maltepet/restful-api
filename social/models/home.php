<?php
	
	class HomeModel {
		
		public function Index() {
			$data = array('page_title' => 'Login or Register');
			return $data;
		}
		
		public function Login() {
			$data = array('page_title' => 'Login');
			return $data;
		}
	}