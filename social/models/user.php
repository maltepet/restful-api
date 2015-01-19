<?php
	
	class UserModel {
		
		public $user_id;
		
		public function __construct() {
			
		}
		
		public function ListUsers() {
			$url = 'http://rest.mattaltepeter.com/users/list';
			$r = json_decode(file_get_contents($url));
			
			if ( $r->success ) {
				$data = $r->data;
			}
			
			$data = array('page_title' => 'All Users', 'data' => $data);
			
			return $data;
		}
		public function Profile() {
			$url = 'http://rest.mattaltepeter.com/users/profile/' . $this->user_id;
			$r = json_decode(file_get_contents($url));
			
			if ( $r->success ) {
				$avatar = $r->data->avatar;
				$bio = $r->data->bio;
				$user_id = $r->data->user_id;
				$name = base64_decode($r->data->name);
				$screen_name = base64_decode($r->data->screen_name);
				$num_favorites = $r->data->favorite_count;
				$num_follwers = $r->data->followers;
				$num_following = $r->data->following;
				$num_posts = $r->data->post_count;
				$posts = $r->data->posts;
				
				$data = array('page_title' => 'Your Profile', 'user_id' => $user_id, 'avatar' => $avatar, 'bio' => $bio, 'name' => $name, 'screen_name' => $screen_name, 'num_favorites' => $num_favorites, 'num_followers' => $num_follwers, 'num_following' => $num_following, 'num_posts' => $num_posts, 'posts' => $posts);
				//$data = array('data' => $r);

			}
			
			//$data = array('data' => $url);
			
			return $data;
			
		}
		
		public function Settings() {
			return array('page_title' => 'Settings');
		}
	}