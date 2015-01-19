<?php
	
	
	class UserController {
		
		/*public function emailCheck() {
			if ( isset($_POST['value']) ) {
		
				//$data = array('email' => base64_encode($_POST['value']));
				$data = base64_encode($_POST['value']);
				
				$request = new ApiCall("users/checkemail?email={$data}");
				$r = $request->get($data); 
				
				echo json_encode($r);
			}
		}
		
		public function screen_nameCheck() {
			if ( isset($_POST['value']) ) {
				
				$data = base64_encode($_POST['value']);
				
				$request = new ApiCall("users/checksn?screen_name={$data}");
				$r = $request->get($data);
				
				echo json_encode($r);
			}
		}*/
		
		public function register() {
			$view = new View('register');
			$view->assign('title', 'Register');
		}
		
		public function registerAction() {
			
			unset($_SESSION['error']);
			
			if ( isset($_POST['reg_sub']) ) { 
				
				$arr = array('first', 'last', 'email', 'screen_name', 'password');
				validate($arr, 'register');	
				
				$email       = base64_encode($_POST['screen_name']);
				$password    = base64_encode($_POST['password']);
				$screen_name = base64_encode($_POST['screen_name']);
				$name        = base64_encode($_POST['first'] . $_POST['last']);

				$data = array('email' => $email, 'password' => $password, 'screen_name' => $screen_name, 'name' => $name);

				$request = new ApiCall('users/register');
				$r = $request->post($data);
				
				//var_dump($r);
				
				if ( $r->success ) {
					
					$_SESSION['logged_in'] = true;
					
					$_SESSION['name']        = base64_decode($r->data->name);
					$_SESSION['user_id']     = base64_decode($r->data->user_id);
					$_SESSION['screen_name'] = base64_decode($r->data->screen_name); 
					
					header('Location: dashboard');
					exit();
				}
				
				else {
					$_SESSION['error'] = $r->error_message;
					header('Location: register');
					exit();
				}
				
			}
		}
		
		
		public function login() {
			$view = new View('login');
			$view->assign('title', 'Login');
		}
		
		public function loginAction() {
			
			unset($_SESSION['error']);
			
			if ( isset($_POST['login_sub']) ) {
					
				$arr = array('email', 'password');
				validate($arr, 'login');
				
				$request = new ApiCall('users/login');
				$r = $request->post(array('email' => base64_encode($_POST['email']), 'password' => base64_encode($_POST['password'])));
				
				if ( $r->success ) {
					
					$_SESSION['logged_in'] = true;
					
					$_SESSION['name'] = base64_decode($r->data->name);
					$_SESSION['user_id'] = $r->data->user_id;
					$_SESSION['screen_name'] = base64_decode($r->data->screen_name);
					
					header('Location: dashboard');
					exit();
				}
				
				else {
					$_SESSION['error'] = $r->error_message;
					header('Location: login');
					exit();
				}
						
			}
		}
		
		public function dashboard() {
			$view = new View('dashboard');
			$view->assign('title', 'User Dashboard');
		}
		
		public function view() {}
		
		public function logout() {
			unset($_SESSION);
			session_unset();
			session_destroy();
			
			header('Location: login');
		}
	}