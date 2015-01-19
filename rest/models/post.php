<?php
	
	class Post {
		
		public $post_id;
		public $user_id;
		public $post_time;
		public $post_body;
		public $post_screen_name;
		public $post_attachment;
		
		public $file;
		public $file_name;
		public $temp_file;
		public $file_loc;
		
		public static function getSinglePostByPostId($post_id) {
			
			$db = new Database();
			$db->query('select * from posts where post_id = :post_id');
			$db->bind(':post_id', $post_id);
			
			$r = $db->single();
			
			if ( !$r ) {
				$result['success'] = false;
				$result['error_message'] = 'This post does not exist. It may have been deleted by the original poster';
			}
		}
		
		public static function getPostsByUserId($user_id) {
			
			$db = new Database();
			$db->query('select posts.post_id, posts.body, posts.time, posts.attachment, users.id, users.name, users.screen_name, users.avatar from posts join users on posts.user_id = users.id where posts.user_id = :user_id order by posts.time desc');
			$db->bind(':user_id', $user_id);
			
			$r = $db->resultSet();
			
			$count = $db->rowCount();
			
			$data = array('posts' => $r, 'post_count' => $count);
			
			if ( $count > 0 ) {
				$result['success'] = true;
				$result['data'] = array('posts' => $r, 'post_count' => $count);
			}
			
			else if ( $count == 0 ) {
				$result['success'] = true;
				$result['data'] = array('posts' => null, 'post_count' => 0);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem fetching the posts.';
			}
			
			return $result;
		}
		
		public static function getNumPostsByUserId($user_id) {
			$db = new Database();
			$db->query('select id from posts where user_id = :user_id');
			$db->bind(':user_id', $user_id);
			
			$r = $db->resultSet();
			
			$result['data'] = $db->rowCount();
			
			return $db->rowCount();
		}
		
		public function savePost() {
			
			if ( $this->file ) {
				$file_upload_res = move_uploaded_file($this->temp_file, $this->file_loc);
			}
		
			$db = new Database();
			$db->query('insert into posts(body, user_id, time, attachment) values(:body, :user_id, :time, :file)');
			$db->bind(':body', $this->post_body);
			$db->bind(':user_id', $this->user_id);
			$db->bind(':time', $this->post_time);
			$db->bind(':file', $this->file_name);
			
			$r = $db->execute();
			
			if ( $r ) {
				$this->post_id = $db->lastInsertId();
				
				$db->query('select id, name, screen_name, avatar from users where id = :user_id');
				$db->bind(':user_id', $this->user_id);
				
				$user_data = $db->single();
				
				$result['success'] = true;
				$result['data'] = array('post' => $this->toArray(), 'user' => $user_data);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem saving the post to the database. Please try again later';
			}

			
			
			return $result;
			
			
		}
		
		public function uploadFile() {
			
			$result['success'] = move_uploaded_file($this->temp_file, $this->file_name);
			$result['data'] = array('file_name' => $this->file_name);
			
			return $result;
		}
		
		public static function deletePost($post_id) {
			
			$db = new Database();
			$db->query('delete from posts where post_id = :post_id');
			$db->bind(':post_id', $post_id);
			
			$r = $db->execute();
			
			if ( $r ) {
				$db->query('delete from favorites where post_id = :post_id');
				$result['success'] = true;
				$result['data'] = $post_id;
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem deleting the post';
			}
			
			return $result;
			
		}
		
		public static function favoritePost($user_id, $post_id) {
			
			$db = new Database();
			$db->query('insert into favorites(user_id, post_id) values(:u_id, :p_id)');
			$db->bind(':u_id', $user_id);
			$db->bind(':p_id', $post_id);
			
			$r = $db->execute();
			
			if ( $r ) {
				$result['success'] = true;
				$result['data'] = array('post_id' => $post_id, 'user_id' => $user_id);
			}
			
			else {
				$result['success'] = false;
				$result['error_message'] = 'There was a problem favoriting the post';
			}
			
			return $result;
		}
		
		
		public function toArray() {
			return array(
				'post_id' => $this->post_id,
				'user_id' => $this->user_id,
				'post_time' => $this->post_time,
				'post_body' => $this->post_body,
				'post_attachment' => $this->file_name
			);
		}
		
		
	}