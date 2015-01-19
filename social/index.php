<?php
	
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	
	session_start();
	
	define('ROOT', dirname(__FILE__));
	define("HTTP", "http://social.mattaltepeter.com/");
	
	include_once 'views/view.php';

	//require the general classes
	require 'classes/loader.php';
	require 'classes/basecontroller.php';
	require 'classes/basemodel.php';
	require 'classes/apicall.php';
	
	//require the model classes
	require 'models/home.php';
	require 'models/user.php';
	
	//require the controller classes
	require 'controllers/home.php';
	require 'controllers/user.php';
	require 'controllers/posts.php';
	
	
	require 'functions.php';
	
	//create the controller and execute the action
	$loader = new Loader($_GET);
	$controller = $loader->CreateController();
	$controller->ExecuteAction();
	
		
		