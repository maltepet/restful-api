<?php
	
	class User {
		
		public $name;
		public $email;
		public $user_id;
		public $bio;
		public $password;
		public $avatar;
		public $screen_name;
		
		public $file;
		public $file_name;
		public $temp_file;
		public $file_loc;
		
		public function getProfileData() {
			
			$db = new Database();
			
			if ( isset($this->screen_name) ) {
				$db->query('select * from users where screen_name = :screen_name');
				$db->bind(':screen_name', $this->screen_name);
				$var = 'test';
			}
			
			else if ( isset($this->user_id) ) {
				$db->query('select * from users where id = :user_id');
				$db->bind(':user_id', $this->user_id);
			}
			
			$user_data = $db->single();
			
			$user_id = $user_data['id'];
			$bio  = $user_data['bio'];
			$name = $user_data['name'];
			$screen_name = $user_data['screen_name'];
			$avatar = $user_data['avatar'];
			
			$db->query('select posts.post_id, posts.body, posts.time, posts.attachment, posts.user_id, users.name, users.screen_name, users.avatar from posts join users on posts.user_id = users.id where posts.user_id = :user_id order by posts.time desc');
			$db->bind(':user_id', $user_id);
			
			$posts = $db->resultset();
			$count = $db->rowCount();
			$favorites = array();
			
			foreach( $posts as $post ) {
				$db->query('select * from favorites where post_id = :post_id');
				$db->bind(':post_id', $post['post_id']);
				$db->resultset();
				$favorites[$post['post_id']] = $db->rowCount();
			}
			
			$db->query('select * from favorites where user_id = :user_id');
			$db->bind(':user_id', $user_id);
			
			$db->resultSet();
			$favorite_count = $db->rowCount();
			
			//select follower/following data
			
			$db->query('select * from followers where follower_id = :f_id');
			$db->bind(':f_id', $user_id);
			
			$r = $db->resultset();
			$following = $db->rowCount();
			
			$db->query('select * from followers where user_id = :u_id');
			$db->bind(':u_id', $user_id);
			
			$r = $db->resultset();
			$followers = $db->rowCount();
			
			
			//$favorites = 0;
			
			$data = array('user_id' => $user_id, 'avatar' => $avatar, 'bio' => $bio, 'name' => $name, 'screen_name' => $screen_name, 'favorites' => $favorites, 'favorite_count' => $favorite_count, 'followers' => $followers, 'following' => $following, 'followers' => $followers, 'post_count' => $count, 'posts' => $posts);
			$result['success'] = true;
			$result['data'] = $data;
			
			return $result;
		}
		
		public static function getAllUsers() {
			
			$db = new Database();
			
			$db->query('select id, name, screen_name, bio, avatar from users');
			
			$r = $db->resultSet();
			
			if ( $r ) {
				$result['success'] = true;
				$result['data'] = array('users' => $r);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'Problem fetching users';
			}
			
			return $result;
		}
		
		public static function getSingleUserById($user_id) {
			
			$db = new Database();
			$db->query('select id, email, screen_name, name from users where id = :id');
			$db->bind(':id', $user_id);
			
			return $db->single();
		}
		
		public static function getSingleUserByScreenName($screen_name) {
			
			$db = new Database();
			$db->query('select id, email, screen_name, name from users where screen_name = :screen_name');
			$db->bind(':screen_name', $screen_name);
			
			return $db->single();
		}
		
		public function save() {
			
			$db = new Database();
			
			$db->query('select * from users where email = :email or screen_name = :screen_name');
			$db->bind(':email', $this->email);
			$db->bind(':screen_name', $this->screen_name);
			
			$r = $db->single();
			
			if ( $db->rowCount() > 0 ) {
				$result['success'] = false;
				$result['error_message'] = 'The email and/or screen name you provided is already registered';
				
				return $result;
			}
			
			else {
				$pics = array('default1.png', 'default2.png', 'default3.png', 'default4.png', 'default5.png', 'default6.png', 'default7.png');
				$pic_key = rand(0, 6);
				$this->avatar = 'http://rest.mattaltepeter.com/uploads/' . $pics[$pic_key];
				
				$db->query('insert into users(email, password, screen_name, name, avatar) values(:email, :password, :screen_name, :name, :attachment)');
				$db->bind(':email', $this->email);
				$db->bind(':password', $this->password);
				$db->bind(':screen_name', $this->screen_name);
				$db->bind(':name', $this->name);
				$db->bind(':attachment', $this->avatar);
				
				$r = $db->execute();
				
				if ( !$r ) {
					$result['data'] = null;
					$result['success'] = false;
				}
				
				$this->user_id = $db->lastInsertId();
				
				$result['data'] = array('name' => $this->name, 'user_id' => $this->user_id, 'screen_name' => $this->screen_name, 'avatar' => $this->avatar);
				$result['success'] = true;
			}
			
			return $result;
		}
		
		public function updateProfile() {
			
			$db = new Database();
			$db->query('update users set bio = :bio, name = :name where id = :user_id');
			$db->bind(':bio', $this->bio);
			$db->bind(':name', $this->name);
			$db->bind(':user_id', $this->user_id);
			
			$r = $db->execute();
			
			if ( $r ) {
				$result['success'] = true;
				$result['data'] = array('bio' => $this->bio, 'name' => $this->name, 'user_id' => $this->user_id);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem saving the information to the database';
			}
			
			return $result;
		}
		
		public function login() {
			
			$db = new Database();
			
			$db->query('select * from users where email = :email');
			$db->bind(':email', $this->email);
			
			$r = $db->single();
			
			if ( $r ) {
				
				if ( $this->password == trim($r['password']) ) {
					//success
					$result['success'] = true;
					$result['data'] = array('user_id' => $r['id'], 'name' => $r['name'], 'screen_name' => $r['screen_name'], 'avatar' => $r['avatar']);
					
				}
				
				else {
					//wrong pass
					$result['success'] = false;
					$result['error_message'] = 'The password you entered does not match the one we have on file';
				}
			}
			
			else {
				//no email found
				$result['success'] = false;
				$result['error_message'] = 'We could not find an account associated with the email you provided';
			}
			
			return $result;
		}
		
		public function lookupEmail() {
			
			$db = new Database();
			
			$db->query('select * from users where email = :email');
			$db->bind(':email', $this->email);
			
			$err = 'The email you provided is already taken!';
			$suc = 'Email is available!';
			
			$r = $db->single();
			
			$count = $db->rowCount();
			
			if ( $count > 0 ) {
				$result['success'] = true;
				$result['data'] = true;
			}
			
			else {
				$result['success'] = true;
				$result['data'] = false;
			}
			
			return $result;
		}
		
		public function lookupSn() {
			
			$db = new Database();
			
			$db->query('select * from users where screen_name = :screen_name');
			$db->bind(':screen_name', $this->screen_name);
			
			$err = 'The screen name you provided is already taken!';
			$suc = 'Screen name is available!';
			
			$r = $db->single();
			
			$count = $db->rowCount();
			
			if ( $count > 0 ) {
				$result['success'] = true;
				$result['data'] = true;
			}
			
			else {
				$result['success'] = true;
				$result['data'] = false;
			}
			
			return $result;
		}
		
		public function uploadProfilePic() {
			
			if ( $this->file ) {
				$file_upload_res = move_uploaded_file($this->temp_file, $this->file_loc);
				
				$db = new Database();
				$db->query('update users set avatar = :avatar where id = :user_id');
				$db->bind(':user_id', $this->user_id);
				$db->bind(':avatar', $this->file_name);
				
				$r = $db->execute();
				
				if ( $r ) {
					$result['success'] = true;
					$result['data'] = array('new_avatar' => $this->file_name);
				}
				
				else {
					$result['success'] = true;
					$result['error_message'] = 'There was a problem uploading your file to the server. Please try again later.';
				}
				
			}
			
			return $result;	
		}
		
		public static function followUser($user_id, $follower_id) {
			
			$db = new Database();
			$db->query('insert into followers(user_id, follower_id) values(:user_id, :follower_id)');
			$db->bind(':user_id', $user_id);
			$db->bind(':follower_id', $follower_id);
			
			$r = $db->execute();
			
			if ( $r ) {
				$result['success'] = true;
				$result['data'] = array('user_id' => $user_id, 'follower_id' => $follower_id);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem completing your request. Please try again later.';
			}
			
			return $result;
		}
		
		public static function unfollowUser($user_id, $follower_id) {
			
			$db = new Database();
			$db->query('delete from followers where user_id = :user_id and follower_id = :follower_id');
			$db->bind(':user_id', $user_id);
			$db->bind(':follower_id', $follower_id);
			
			$r = $db->execute();
			if ( $r ) {
				$result['success'] = true;
				$result['data'] = array('user_id' => $user_id, 'follower_id' => $follower_id);
			}	
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem completing your request. Please try again later';
			}
			
			return $result;
		}
		
		public static function getFollowing($user_id) {
			
			$db = new Database();
			$db->query('select users.id, users.name, users.screen_name, users.bio, users.avatar from followers join users on followers.user_id = users.id where followers.follower_id = :follower_id');
			$db->bind(':follower_id', $user_id);
			
			$following = $db->resultSet();
			$following_count = $db->rowCount();	
			
			
			if ( $following_count > 0 ) {
				$result['success'] = true;
				$result['data'] = array('following_count' => $following_count, 'following' => $following);
			}
			
			else if ( $following_count == 0 ) {
				$result['success'] = true;
				$result['data'] = array('following_count' => 0, 'following' => null);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem completing your request. Please try again later.';
			}
			
			return $result;
		}
		
		public static function getFollowers($user_id) {
			
			$db = new Database();
			$db->query('select users.id, users.name, users.screen_name, users.bio, users.avatar from followers join users on followers.follower_id = users.id where followers.user_id = :user_id');
			$db->bind(':user_id', $user_id);
			
			$following = $db->resultSet();
			$following_count = $db->rowCount();	
			
			if ( $following_count > 0 ) {
				$result['success'] = true;
				$result['data'] = array('follower_count' => $following_count, 'followers' => $following);
			}
			
			else if ( $following_count == 0 ) {
				$result['success'] = true;
				$result['data'] = array('follower_count' => 0, 'followers' => null);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem completing your request. Please try again later.';
			}
			
			return $result;
			
		}
		
		public static function getFavorites($user_id) {
			
			$db = new Database();
			$db->query('select favorites.user_id, favorites.post_id, posts.post_id, posts.user_id, posts.body, posts.time, posts.attachment, users.id, users.name, users.screen_name, users.avatar from favorites join posts on favorites.post_id = posts.post_id join users on posts.user_id = users.id where favorites.user_id = :user_id order by posts.time desc');
			$db->bind(':user_id', $user_id);
			
			$favorites = $db->resultset();
			$favorite_count = $db->rowCount();
			
			if ( $favorite_count > 0 ) {
				$result['success'] = true;
				$result['data'] = array('favorite_count' => $favorite_count, 'favorites' => $favorites);
			}
			
			else if ( $favorite_count == 0 ) {
				$result['success'] = true;
				$result['data'] = array('favorite_count' => 0, 'favorites' => null);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem fetching the data. Please try again later.';
			}
			
			return $result;
		}
		
		public static function isFollowing($user_id, $session_id) {
			
			$db = new Database();
			$db->query('select * from followers where follower_id = :follower_id and user_id = :user_id');
			$db->bind(':follower_id', $session_id);
			$db->bind(':user_id', $user_id);
			
			$r = $db->single();
			$count = $db->rowCount();
			
			if ( $count == 1 ) {
				$result['success'] = true;
				$result['data'] = true;
			}
			
			else if ( $count == 0 ) {
				$result['success'] = true;
				$result['data'] = false;
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem fetching the data.';
			}
			
			return $result;
		}
		
		
		public function toArray() {
			
			return array(
				'name' => $this->name,
				'user_id' => $this->user_id,
				'screen_name' => $this->screen_name,
				'bio' => '',
				'avatar' => ''
			);	
		}
	}