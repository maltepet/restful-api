<?php
	
	class Home extends BaseController {
		
		protected function index() {
			$viewmodel = new HomeModel();
			$this->ReturnView($viewmodel->Index(), true);
		}
		
		protected function login() {
			$viewmodel = new HomeModel();
			$this->ReturnView($viewmodel->Login(), true);
		}
		
		public function logout() {
			session_unset();
			session_destroy();
			
			header('Location: http://social.mattaltepeter.com');
			exit();
		}
		
		public function checksn() {
			$data = array('screen_name' => base64_encode($_POST['screen_name']));
			
			$request = new ApiCall('users/checksn');
			$r = $request->post($data);
			
			echo json_encode($r);
		}
		
		public function checkemail() {
			$data = array('email' => base64_encode($_POST['email']));
			
			$request = new ApiCall('users/checkemail');
			$r = $request->post($data);
			
			echo json_encode($r);
		}
		
		public function register() {
			//var_dump($_POST);
			unset($_SESSION['error']);
			
			if ( isset($_POST['reg_sub']) ) { 
				
				$arr = array('first', 'last', 'email', 'screen_name', 'password');
				//echo 'ready to go';
				validate($arr, 'home/index');	
				//echo 'valid';
				$email       = base64_encode($_POST['email']);
				$password    = base64_encode($_POST['password']);
				$screen_name = base64_encode($_POST['screen_name']);
				$name        = base64_encode($_POST['first'] . ' ' . $_POST['last']);

				$data = array('email' => $email, 'password' => $password, 'screen_name' => $screen_name, 'name' => $name);

				$request = new ApiCall('users/register');
				$r = $request->post($data);
				
				if ( $r->success ) {
					
					$_SESSION['logged_in'] = true;
					
					$_SESSION['name']        = base64_decode($r->data->name);
					$_SESSION['user_id']     = $r->data->user_id;
					$_SESSION['screen_name'] = base64_decode($r->data->screen_name); 
					$_SESSION['avatar'] = $r->data->avatar;
					$url = "http://social.mattaltepeter.com/users/profile/{$_SESSION['screen_name']}";
					header("Location: {$url}");
					exit();
				}
				
				else {
					$_SESSION['error'] = $r->error_message;
					header('Location: index');
					exit();
				}
				
			}
		}
		
		public function signin() {
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
					$_SESSION['avatar'] = $r->data->avatar;
					
					$url = "http://social.mattaltepeter.com/users/profile/{$_SESSION['screen_name']}";
					header("Location: {$url}");
					exit();
				}
				
				else {
					$_SESSION['error'] = $r->error_message;
					header('Location: login');
					exit();
				}
						
			}
			
			//var_dump($_POST);
		}
		
		
	}