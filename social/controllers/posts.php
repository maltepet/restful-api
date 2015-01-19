<?php
	
	class Posts extends BaseController {
		
		public function newPost() {
			
			$timestamp = date('Y-m-d G:i:s');
			$body = $_POST['post_body'];
			
			if ( ($_FILES['post_file']['name'] != '' ) ) {
				$file_name = $_FILES['post_file']['tmp_name'];
				$path_info = pathinfo($_FILES['post_file']['name']);
				$ext = $path_info['extension'];
				$new_name = uniqid() . '.' . $ext;	
				
				$data = array('user_id' => $_SESSION['user_id'], 'body' => $body, 'time' => $timestamp, 'file' => true, 'file_contents' => '@' . $file_name, 'new_name' => $new_name);
			}
			
			else {
				$file_name = "";
				$ext = "";
				$new_name = "";
				
				$data = array('user_id' => $_SESSION['user_id'], 'body' => $body, 'time' => $timestamp);
			}
			
					
			
			
						
			$request = new ApiCall('posts/create');
			$r = $request->post($data, false);
			
			if ( $r->success ) {
				$post = $r->data->post;
				$user = $r->data->user;
				
				$post_id = $post->post_id;
				$time = time_ago($post->post_time);
				$body = $post->post_body;
				$file = $post->post_attachment;
				
				$user_id = $user->id;
				$name = base64_decode($user->name);
				$screen_name = base64_decode($user->screen_name);
				$avatar = $user->avatar;
				
				$html = '';
				
				if ( $file == null ) {
					$img_div = '<div><br /></div>';
				}
				
				else {
					$img_div = '<div class="image"><a data-featherlight="' . $file . '"><img src="' . $file . '" class="ui rounded image" style="max-width: 98%;" /></a></div>';
				}
				
				$html .= "<div id='{$post_id}'>";
			
				$html .= "<div class='comment'>";
				
				if ( $_SESSION['user_id'] == $user_id ) {
					$html .= "<a href='#' class='delete-link right' data-post-id='{$post_id}'><i class='remove icon teal'></i></a>";
				}
				
				$html .= "<a class='avatar'>";
				$html .= "<img src='{$avatar}' height='48' width='48' />";
				$html .= "</a>";
				$html .= "<div class='content'>";
				$html .= "<a class='author'>{$name}<span class='metadata'> &middot; $screen_name </span></a>";
				$html .= "<div class='metadata' style='margin-left: 0;'>";
				$html .= "<span class='date'> &middot; {$time} </span>";
				$html .= "</div>";
				$html .= "<div class='text'>";
				$html .= $body;
				$html .= "</div>";
				$html .= $img_div;
				$html .= "</div><br />";

				$html .= "</div>";
				$html .= "</div>";
				
				$result = array('success' => true, 'html' => $html);
			}
			
			else {
				$result = array('success' => false);
			}
			
			echo json_encode($result);
		}
		
		public function favorite() {
			
			$post_id = $_POST['post_id'];
			
			$data = array('user_id' => $_SESSION['user_id'], 'post_id' => $post_id);
			
			$request = new ApiCall('posts/favorite');
			$r = $request->post($data);
			
			echo json_encode($r);
		}
	}