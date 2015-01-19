<?php
	
	class PostController {
		
		private $_params;
		
		public function __construct($params) {
			$this->_params = $params;
		}
		
		/*
			get single post by post_id
		*/
		
		public function singlepostAction() { 
			
			$post = Post::getSinglePostByPostId($post_id);
			
			return $post;
			
		}
		
		/*
			get all posts by user_id/screen_name
		*/
		
		public function timelineAction() { 
			
			$posts = POST::getPostsByUserId($user_id);
			
			return $posts;
		}
		
		/*
			create new post for the user_id/screen_name 
		*/
		
		public function createAction() { 
			
			$post = new Post();
		
			if ( isset($_POST['file']) ) {
				$post->file = true;
				$uploadFile = SITE_ROOT . '/uploads/' . $_POST['new_name'];
				$post->temp_file = $_FILES['file_contents']['tmp_name'];
				$post->file_loc = $uploadFile;
				$post->file_name = 'http://rest.mattaltepeter.com/uploads/' . $_POST['new_name'];
			}
			
			else {
				$post->file = false;
				$post->temp_file = null;
				$post->file_loc = null;
				$post->file_name = null;
			}
			
			$post->user_id   = $_POST['user_id'];
			$post->post_body = $_POST['body'];
			$post->post_time = $_POST['time'];
			
			
			$result = $post->savePost();
			
			//$result['success'] = true;
			//$result['data'] = array('file_name' => $file);

			return $result;
		}
		
		/*
			delete post by post_id
		*/
		
		public function deleteAction() {
			
			$result = POST::deletePost($this->_params['id']);
			
			return $result;
		}
		
		public function favoriteAction() {
			
			$result = POST::favoritePost($this->_params->user_id, $this->_params->post_id);
			
			return $result;
		}
		
		/*
			edit post by post_id
		*/
		
		public function editAction() { }
			
	}