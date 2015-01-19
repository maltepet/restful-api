<?php
	
	class Users extends BaseController {
		
		protected function profile() {
			$viewmodel = new UserModel();
			$viewmodel->user_id = $this->urlvalues['id'];
			$this->ReturnView($viewmodel->Profile(), true);
		}
		
		protected function settings() {
			$viewmodel = new UserModel();
			$this->ReturnView($viewmodel->Settings(), true);
		}
		
		public function editprofile() {
			$data = array('user_id' => $_SESSION['user_id'], 'bio' => $_POST['bio'], 'name' => base64_encode($_POST['name']));
			
			$request = new ApiCall('users/update');
			$r = $request->post($data);
			
			//$result = array();
			
			echo json_encode($r);
		}
		
		
		public function changepic() {
			
			$file_name = $_FILES['image']['tmp_name'];
			$path_info = pathinfo($_FILES['image']['name']);
			$ext = $path_info['extension'];
			$new_name = uniqid() . '.' . $ext;	
			
			$data = array('user_id' => $_SESSION['user_id'], 'file' => true, 'file_contents' => '@' . $file_name, 'new_name' => $new_name);
			
			$request = new ApiCall('user/uploadprofilepic');
			$r = $request->post($data, false);
			
			if ( $r->success ) {
				$_SESSION['avatar'] = $r->data->new_avatar;
			}
			echo json_encode($r);
		}
		
		public function followuser() {
			
			$user_id = $_POST['user_id'];
			
			$data = array('user_id' => $user_id, 'follower_id' => $_SESSION['user_id']);
			
			$request = new ApiCall('user/follow');
			$r = $request->post($data);
			
			echo json_encode($r);
			
		}
		
		public function unfollow() {
			$user_id = $_POST['user_id'];
			
			$data = array('user_id' => $user_id, 'follower_id' => $_SESSION['user_id']);
			
			$request = new ApiCall('user/unfollow');
			$r = $request->post($data);
			
			echo json_encode($r);
		}
		
		public function getfollowers() {
			
			$user_id = $_POST['user_id'];
			
			$data = json_decode(file_get_contents("http://rest.mattaltepeter.com/users/followers/{$user_id}"));
			
			if ( $data->success ) {
				
				if ( $data->data->follower_count > 0 ) {
					$users_following = $data->data->followers;
					
					$html = '';
	
					foreach( $users_following as $user ) {
						$user_id = $user->id;
						$name = base64_decode($user->name);
						$screen_name = base64_decode($user->screen_name);
						$bio = $user->bio;
						$avatar = $user->avatar;
						$profile_url = HTTP . '/users/profile/' . $screen_name;
						
						$html .= "<li id='$user_id'><div class='ui card' data-equalizer-watch>";
						$html .= "<div class='image'><a href='{$profile_url}' style='width: 100%;'><img src='$avatar' style='width: 100%;' /></a></div>";
						$html .= "<div class='content'>";
					    $html .= "<a class='header left' id='profile-name' href='" . HTTP . "users/profile/$screen_name'>$name</a>";
					    $html .= "<div style='clear:both'></div>";
					    $html .= "<div class='meta'>";
					    $html .= "<span class='date'>$screen_name</span>";
					    $html .= "</div>";
					    
					    if ( is_following($user_id) ) {
						    $html .= "<div class='ui labeled icon green button mini following-btn' style='margin-top: 10px; margin-bottom: 10px;' data-user-id='$user_id'>";
						    $html .= "<i class='checkmark icon'></i>";
						    $html .= "Following";
						    $html .= "</div>";
					    }
					    
					    else {
						    $html .= "<div class='ui labeled icon teal button mini follow-btn' style='margin-top: 10px; margin-bottom: 10px;' data-user-id='$user_id'>";
						    $html .= "<i class='plus icon'></i>";
						    $html .= "Follow";
						    $html .= "</div>";
					    }
					    
					    $html .= "<div class='description' id='profile-bio'>";
					    $html .= $bio;
					    $html .= "</div>";
		
						$html .= "</div>";
						$html .= "</div></li>";
						
						
					
					}
					
					$result = array('success' => true,  'html' => $html);
				}
				
				else {
					$result = array('success' => true, 'html' => 'This person has no followers!');
				}
			}
			
			else {
				$result = array('success' => false);
			}
			
			echo json_encode($result);
		}
		
		public function getfollowing() {
			
			$user_id = $_POST['user_id'];
			
			$data = json_decode(file_get_contents("http://rest.mattaltepeter.com/users/following/{$user_id}"));
			
			if ( $data->success ) {
				
				if ( $data->data->following_count ) {
					$users_following = $data->data->following;
					
					$html = '';
	
					foreach( $users_following as $user ) {
						$user_id = $user->id;
						$name = base64_decode($user->name);
						$screen_name = base64_decode($user->screen_name);
						$bio = $user->bio;
						$avatar = $user->avatar;
						$profile_url = HTTP . '/users/profile/' . $screen_name;
						
						$html .= "<li id='$user_id'><div class='ui card' data-equalizer-watch>";
						$html .= "<div class='image'><a href='$profile_url' style='width: 100%;'><img src='$avatar' style='width: 100%;' /></a></div>";
						$html .= "<div class='content'>";
					    $html .= "<a class='header left' id='profile-name' href='" . HTTP . "users/profile/$screen_name'>$name</a>";
					    $html .= "<div style='clear:both'></div>";
					    $html .= "<div class='meta'>";
					    $html .= "<span class='date'>$screen_name</span>";
					    $html .= "</div>";
					    
					    /*if ( $_SESSION['user_id'] == $user_id) {
						    $html = 'unfollow btn';
					    }
					    else {
						    $html .= "<div class='ui labeled icon teal button mini follow-btn' style='margin-top: 10px; margin-bottom: 10px;' data-user-id='$user_id'>";
							$html .= "<i class='plus icon'></i>";
							$html .= "Follow";
							$html .= "</div>";
					    }*/
					    
					    if ( is_following($user_id) ) {
						    $html .= "<div class='ui labeled icon green button mini following-btn' style='margin-top: 10px; margin-bottom: 10px;' data-user-id='$user_id'>";
						    $html .= "<i class='checkmark icon'></i>";
						    $html .= "Following";
						    $html .= "</div>";
					    }
					    
					    else {
						    $html .= "<div class='ui labeled icon teal button mini follow-btn' style='margin-top: 10px; margin-bottom: 10px;' data-user-id='$user_id'>";
						    $html .= "<i class='plus icon'></i>";
						    $html .= "Follow";
						    $html .= "</div>";
					    }
					    
					    $html .= "<div class='description' id='profile-bio'>";
					    $html .= $bio;
					    $html .= "</div>";
		
						$html .= "</div>";
						$html .= "</div></li>";
						
						
					
					}
					
					$result = array('success' => true,  'html' => $html);
				}
				
				else {
					$result = array('success' => true, 'html' => 'This person is not following anybody!');
				}
			}
			
			else {
				$result = array('success' => false);
			}
			
			echo json_encode($result);
			
		}
		
		public function getfavorites() {
			
			$user_id = $_POST['user_id'];
			
			$data = json_decode(file_get_contents("http://rest.mattaltepeter.com/users/favorites/{$user_id}"));
			
			if ( $data->success ) {
				
				if ( $data->data->favorite_count > 0 ) {
					$favs = $data->data->favorites;
					$html = '';
					$count = 0;
					
					foreach( $favs as $fav )  {
						
						$user_id = $fav->id;
						$post_id = $fav->post_id;
						$post_body = $fav->body;
						$post_time = time_ago($fav->time);
						$post_file = $fav->attachment;
						$name = base64_decode($fav->name);
						$screen_name = base64_decode($fav->screen_name);
						$avatar = $fav->avatar;
						
						if ( $post_file == null ) {
							$img_div = '';
						}
						
						else {
							$img_div = '<div class="image"><a data-featherlight="' . $post_file . '"><img src="' . $post_file . '" class="ui rounded image" style="max-width: 98%;" /></a></div>';
						}
						
						if ( $count > 0 ) {
							$html .= "<div class='ui divider'></div>";
						}
						$html .= "<div class='comment' id='{$post_id}'>";
						
						$html .= "<a class='avatar'>";
						$html .= "<img src='{$avatar}' height='48' width='48' />";
						$html .= "</a>";
						$html .= "<div class='content'>";
						$html .= "<a class='author'>{$name}<span class='metadata'> &middot; $screen_name </span></a>";
						$html .= "<div class='metadata' style='margin-left: 0;'>";
						$html .= "<span class='date'> &middot; {$post_time} </span>";
						$html .= "</div>";
						$html .= "<div class='text'>";
						$html .= $post_body;
						$html .= "</div>";
						$html .= $img_div;
						$html .= "</div><br />";
						/*$html .= "<div class='actions'>";
						$html .= "<a class='favorite yellow' data-post-id='{$post_id}'><i class='large empty star icon'></i></a>667";
						$html .= "</div>";*/
						$html .= "</div>";
						
						$count++;
					}
					
						$msg = 'There are no favorites!';
						$result = array('success' => true, 'html' => $msg);
					
						$result = array('success' => true, 'html' => $html);
				}
				
				else {
					$result = array('success' => true, 'html' => 'There are no favorites to show!');
				}
				
			}
			
			else {
				$result = array('success' => false);
			}
			
			echo json_encode($result);
		}
		
		public function getposts() {
			$user_id = $_POST['user_id'];
			
			$data = json_decode(file_get_contents("http://rest.mattaltepeter.com/users/posts/{$user_id}"));
			
			if ( $data->success ) {
				if ( $data->data->post_count ) {
					$posts = $data->data->posts;
					$html = '';
					$count = 0;
					
					foreach( $posts as $post )  {
						
						$user_id = $post->id;
						$name = base64_decode($post->name);
						$screen_name = base64_decode($post->screen_name);
						$avatar = $post->avatar;
						
						$post_id = $post->post_id;
						$post_body = $post->body;
						$post_time = time_ago($post->time);
						$post_file = $post->attachment;
						
						if ( $post_file == null ) {
							$img_div = '<div><br /></div>';
						}
						
						else {
							$img_div = '<div class="image"><a data-featherlight="' . $post_file . '"><img src="' . $post_file . '" class="ui rounded image" style="max-width: 98%;" /></a></div>';
						}
						
						$html .= "<div id='{$post_id}'>";
						if ( $count > 0 ) {
							$html .= "<div class='ui divider'></div>";
						}
						$html .= "<div class='comment' id='{$post_id}'>";
						
						if ( $_SESSION['user_id'] == $user_id ) {
							$html .= "<a href='#' class='delete-link right' data-post-id='{$post_id}'><i class='remove icon teal'></i></a>";
						}
						
						$html .= "<a class='avatar'>";
						$html .= "<img src='{$avatar}' height='48' width='48' />";
						$html .= "</a>";
						$html .= "<div class='content'>";
						$html .= "<a class='author'>{$name}<span class='metadata'> &middot; $screen_name </span></a>";
						$html .= "<div class='metadata' style='margin-left: 0;'>";
						$html .= "<span class='date'> &middot; {$post_time} </span>";
						$html .= "</div>";
						$html .= "<div class='text'>";
						$html .= $post_body;
						$html .= "</div>";
						$html .= $img_div;
						$html .= "</div><br />";
						/*$html .= "<div class='actions'>";
						$html .= "<a class='favorite yellow' data-post-id='{$post_id}'><i class='large empty star icon'></i></a>667";
						$html .= "</div>";*/
						$html .= "</div>";
						$html .= "</div>";
						$count++;
					}
					
					$result = array('success' => true, 'html' => $html);
				}
				
				else {
					$result = array('success' => true, 'html' => 'There are no posts to show!');
				}
				
			}
			
			else {
				$result = array('success' => false);
			}
			
			echo json_encode($result);
		}
		
		public function listusers() {
			$viewmodel = new UserModel();
			$this->ReturnView($viewmodel->ListUsers(), true);
		}
	}