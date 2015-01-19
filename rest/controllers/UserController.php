<?php
	
	class UserController {
		
		private $_params;
		
		public function __construct($params) {
			$this->_params = $params;
		}
		
		public function listAction() {
			
			/*if ( isset($this->_params['user_id']) ) {
				
				if ( $this->_params['user_id'] != null )
					return User::getSingleUserById($this->_params['user_id']);
			}
			
			else if ( isset($this->_params['screen_name']) ) {
				
				if ( $this->_params['screen_name'] != null )
					return User::getSingleUserByScreenName($this->_params['screen_name']);
			}
			
			else {
				return User::getAllUsers();
			}*/
			
			return User::getAllUsers();
		}
		
		public function registerAction() {
			
			$user = new User();
			
			$user->name			= $this->_params->name;
			$user->email    	= $this->_params->email;
			$user->password 	= $this->_params->password;
			$user->screen_name  = $this->_params->screen_name;
			
			$result = $user->save();
			
			return $result;
		
		}
		
		public function loginAction() {
			
			$user = new User();
			
			$user->email 	= $this->_params->email;
			$user->password = $this->_params->password;
			
			$result = $user->login();
			
			return $result;
			
		}
		
		public function checkemailAction() {
			
			$user = new User();
			
			$user->email = $this->_params->email;
			
			$result = $user->lookupEmail();

			return $result;
		}
		
		public function checksnAction() {
			
			$user = new User();
			
			$user->screen_name = $this->_params->screen_name;
			
			$result = $user->lookupSn();
			
			return $result;
		}
		
		public function postsAction() {
			$posts = POST::getPostsByUserId($this->_params['id']);
			
			return $posts;
		}
		
		public function updateAction() {
			$user = new User();
			
			$user->user_id = $this->_params->user_id;
			$user->bio = $this->_params->bio;
			$user->name = $this->_params->name;
			
			$result = $user->updateProfile();
	
			return $result;
		}
		
		public function profileAction() {
			$user = new User();
			//$user->user_id = $this->_params['id'];
			
			if ( isset($this->_params['id']) ) {
				$user->user_id = $this->_params['id'];
			}
			
			else if ( isset($this->_params['screen_name']) ) {
				$user->screen_name = base64_encode($this->_params['screen_name']);
			}
			
			$result = $user->getProfileData();
		
			return $result;
		}
		
		public function uploadprofilepicAction() {
			
			$user = new User();
			
			if ( isset($_POST['file']) ) {
				$user->user_id = $_POST['user_id'];
				
				$user->file = true;
				$uploadFile = SITE_ROOT . '/uploads/' . $_POST['new_name'];
				$user->temp_file = $_FILES['file_contents']['tmp_name'];
				$user->file_loc = $uploadFile;
				$user->file_name = 'http://rest.mattaltepeter.com/uploads/' . $_POST['new_name'];
				
				$result = $user->uploadProfilePic();
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'No file uploaded!';
			}
			
			return $result;
		}
		
		public function followAction() {
			
			$user_id = $this->_params->user_id;
			$follower_id = $this->_params->follower_id;
			
			$result = User::followUser($user_id, $follower_id);
		
			return $result;
		}
		
		public function unfollowAction() {
			$user_id = $this->_params->user_id;
			$follower_id = $this->_params->follower_id;
			
			$result = User::unfollowUser($user_id, $follower_id);
			
			return $result;
		}
		
		public function followingAction() {
			
			$user_id = $this->_params['id'];
			
			$result = User::getFollowing($user_id);	
			
			return $result;
		}
		
		public function followersAction() {
			
			$user_id = $this->_params['id'];
			
			$result = User::getFollowers($user_id);
			
			return $result;
		}
		
		public function favoritesAction() {
			
			$user_id = $this->_params['id'];
			
			$result = User::getFavorites($user_id);
			
			return $result;
		}
		
		public function checkfollowingAction() {
			$user_id = $this->_params->user_id;
			$session_id = $this->_params->session_id;
			
			$result = User::isFollowing($user_id, $session_id);
			
			return $result;
		}
		
		public function getParams() {
			return $this->_params;
		}
		
	}