<?php

	class ApiCall {
		
		public $api_url = 'http://rest.mattaltepeter.com/';
		public $url;
		
		public function __construct($url) {
			$this->url = $this->api_url . $url;
		}
		
		
		function post($params = array(), $json_encode = true) {
			
			if ( $json_encode )
				$ds = json_encode($params);
			else
				$ds = $params;
				
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $ds);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
			//execute the request
			$result = curl_exec($ch);
			
			
			return json_decode($result);
		}

	}