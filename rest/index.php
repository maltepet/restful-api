<?php
	header("Access-Control-Allow-Origin: *");
	header('Content-type: application/json');
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	
	define ('SITE_ROOT', realpath(dirname(__FILE__)));
	define('UPLOAD_DIR', 'rest.mattaltepeter.com/uploads/');
	
	include_once 'models/database.php';
	include_once 'models/user.php';
	include_once 'models/post.php';
	
	$return = array();
	
	$controller = $_GET['controller'];
	$action = $_GET['action'] . 'Action';
	
	if ( isset($_GET['id']) ) {
		$id = $_GET['id'];
		if ( is_numeric($id) ) {
			$params['id'] = $id;
		}
		
		else {
			$params['screen_name'] = $id;
		}
	}
	
	
	
	$model = strtolower(rtrim($controller, 's'));
	$controller = ucwords(rtrim($controller, 's')) . 'Controller';

	$http_method = strtolower($_SERVER['REQUEST_METHOD']);
	
	if ( $http_method == 'get' ) {
		$params = $params;
    }
	
	else if ( $http_method == 'post' ) {
		$params = json_decode(file_get_contents('php://input'));
	}
	

	if ( file_exists("controllers/{$controller}.php") ) {
		include_once "controllers/{$controller}.php";
		//include_once "models/{$model}.php";
	} 
		
	else {
		throw new Exception('Controller is invalid.');
	}
	$stuff = array('controller' => $controller, 'action' => $action, 'id' => $id, 'params' => $params);
	$controller = new $controller($params);
	
	
	if ( (int) method_exists($controller, $action) == 'false' ) {
		$return = array('error_message' => 'method does not exist');
	}
	
	else {
		
		$result = $controller->$action();
		
		if ( $result['success'] == false ) {
			$return['success'] = false;
			$return['error_message'] = $result['error_message'];
		}
		
		else {
			$return['success'] = $result['success'];
			$return['data'] = $result['data'];
		}
		
		
		//echo json_encode($params);
		
		echo json_encode($return);
	}
	
	//echo 'uploaded filename: ' . $_FILES['image']['name'];
	
	