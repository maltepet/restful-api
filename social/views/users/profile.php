<?php
	
	if ( !isset($_SESSION['logged_in']) ) {
		header('Location: ../../home/login');
	}
	
	
?>
<div id="profile-data-attr" data-user-id="<?php echo $viewmodel['user_id']; ?>" style="display: none"></div>
<div class="row">
	<div class="small-12 medium-4 large-4 columns">
		<div class="ui card">
		  <div class="image">
			  <div class="ui active inverted dimmer" style="display: none;" id="profile-pic-loader">
			  	<div class="ui text loader">Loading</div>
  			</div>
		    <a data-featherlight="<?php echo $viewmodel['avatar']; ?>" style="width: 100%;"><img src="<?php echo $viewmodel['avatar']; ?>" style="width: 100%;" /></a>
		  </div>
		  <div class="content">
		    <a class="header left" id="profile-name"><?php echo $viewmodel['name']; ?></a>
		    <?php if ( $_SESSION['user_id'] == $viewmodel['user_id'] ) { ?>
		    <a class="right change-pic-btn fileUpload"  data-content="Click to change your profile picture" data-position="top right">
			    <i class="camera teal icon"></i>
			    <form action="changepic" method="post" id="pic_form">
			    	<input type="file" class="upload" name="new_profile_pic" id="new_profile_pic" />
			    </form>
			</a>
		    <a class="right edit-btn" data-reveal-id="edit-profile-modal" data-content="Click to edit your bio and display name" data-position="top right"><i class="pencil teal icon"></i></a>
		    <?php } ?>
		    <div style="clear:both"></div>
		    <div class="meta">
		      <span class="date"><?php echo $viewmodel['screen_name']; ?></span>
		    </div>
		    <div class="description" id="profile-bio">
		      <?php echo $viewmodel['bio']; ?>
		    </div>
		    
		  </div>
		  	<?php if ( $_SESSION['user_id'] != $viewmodel['user_id'] ) { ?>
		  	<div class="extra content">
			     <div class="ui labeled icon teal button tiny right follow-btn" style="margin-top: 10px; margin-bottom: 10px;" data-user-id="<?php echo $viewmodel['user_id']; ?>">
				  <i class="plus icon"></i>
				  Follow
				</div>
		    </div>
		    <?php } ?>
		</div>

		
			<div class="ui vertical menu" id="profile-controls" style="width: 100%">
			  <a class="active teal item" data-function="posts" data-title="Posts">
			    Posts
			    <div class="ui teal label" id="num_posts"><?php echo $viewmodel['num_posts']; ?></div>
			  </a>
			  <a class="item" data-function="following" data-title="Following">
			    Following
			    <div class="ui label"><?php echo $viewmodel['num_following']; ?></div>
			  </a>
			  <a class="item" data-function="followers" data-title="Followers">
			    Followers
			    <div class="ui label" ><?php echo $viewmodel['num_followers']; ?></div>
			  </a>
			  <!--<a class="item" data-function="favorites" data-title="Favorites">
				  Favorites
				  <div class="ui label"><?php echo $viewmodel['num_favorites']; ?></div>
			  </a>-->
			 </div>
	</div>
	<div class="small-12 medium-8 large-8 columns">
		<?php if ( $_SESSION['user_id'] == $viewmodel['user_id'] ) { ?>
		<div class="ui segment" id="post-form-area">
			<form action="<?php echo HTTP; ?>posts/newPost" method="post" id="post-area" enctype="multipart/form-data">
				<textarea placeholder="What's on your mind?" name="post_body" id="post-body"></textarea>
				<br />
				<div id="attached" style="display: none;">
					<div class="ui divider"></div>
					<div class="ui red basic button tiny right" id="delete-file">
						<i class="remove icon"></i>
						Remove File
					</div>
					<div id="attached-files"></div>
				</div>
				<br />
				<div class="ui teal basic button tiny fileUpload" id="file-select">
					<i class="photo icon"></i>
					Add Photo
					<input type="file" class="upload" id="files" name="post_file" maxlength="1" />
				</div>
				
				<div class="ui labeled icon button teal right tiny fileUpload">
				  <i class="check icon"></i>
				  Post
				  <input type="submit" class="upload" />
				</div>
				<div style="clear:both"></div>
			</form>
			
		</div> <!-- close ui segment post form area -->
		<?php } ?>
		<div id="profile-pages">
			<h2 class="ui top attached header" id="page-title">Posts</h2>
			<div class="ui attached segment" id="profile-views">
				<div id="profile-view-posts" class="active profile-view">
					<div class="ui comments" id="data-posts-container">
						<?php 
						$count = 0;
						foreach( $viewmodel['posts'] as $post ) {
							$post_id = $post->post_id;
							$author = base64_decode($post->name);
							$screen_name = base64_decode($post->screen_name);
							$content = $post->body;
							$time = time_ago($post->time);
							$avatar = $post->avatar;
							$attachment = $post->attachment;
							
							if ( $attachment == null ) {
							$img_div = "<div><br /></div>";
							}
							
							else {
							$img_div = '<div class="image"><a data-featherlight="' . $attachment . '"><img src="' . $attachment . '" class="ui rounded image" style="max-width: 98%;" /></a></div>';
							}
							
							/*if ( $count > 0 ) {
							echo '<div class="ui divider"></div>';
							}*/
							?>
							
							<div id="<?php echo $post_id; ?>">
								<?php
									if ( $count > 0 ) {
							echo '<div class="ui divider"></div>';
							}
							?>
							<div class="comment">
								<?php if ( $_SESSION['user_id'] == $post->user_id ) { ?>
								<a href='#' class='delete-link right' data-post-id="<?php echo $post_id; ?>"><i class='remove icon teal'></i></a>
								<?php } ?>
								<a class="avatar">
									<img src="<?php echo $avatar; ?>" height="48" width="48" />
								</a>
								<div class="content">
									<a class="author"><?php echo $author; ?><span class="metadata"> &middot; <?php echo $screen_name; ?> </span></a>
									<div class="metadata" style="margin-left: 0;">
										<span class="date"> &middot; <?php echo $time; ?></span>
									</div>
									<div class="text">
										<?php echo $content; ?>
									</div>
									<?php echo $img_div; ?>
								</div>
								<br />
								<!--<div class="actions">
									<a class="favorite yellow" data-post-id="<?php echo $post_id; ?>"><i class="large empty star icon"></i></a>667
								</div>-->
							</div> <!-- close comment --></div>
							<?php	
							$count++;
						}
						
						
						?>
						
						<?php 
							if ( $count == 0 )
								$msg = 'There are no posts to show!';
							else
								$msg = '';
						?>
						<strong id="no-post-msg"><?php echo $msg; ?></strong>
					</div> <!-- close ui comments -->
				</div> <!-- close posts -->
				
				<div class="profile-view" id="profile-view-following">
					<div class="row">
						<div class="large-12 columns" data-equalizer>
						<ul class="large-block-grid-2" id="data-following-container">
						</ul>
						</div>
					</div>
				</div> <!-- close profile view following -->
				
				<div class="profile-view" id="profile-view-followers">
					<div class="row" data-equalizer>
						<div class="large-12 columns">
						<ul class="large-block-grid-2" id="data-followers-container">
						</ul>
						</div>
					</div>
				</div>
				
				<div class="profile-view" id="profile-view-favorites">
					<div class="ui comments" id="data-favorites-container">
					</div>
				</div> <!-- close profile view favorites -->
				
			</div> <!-- close profile views -->
	</div> <!-- close pages-->
	</div> <!-- close right column -->
</div> <!-- close page row -->


<div class="reveal-modal small" id="edit-profile-modal" data-reveal>
	<h3>Edit Profile</h3>
	<div class="ui divider"></div>
	<form action="../editprofile" method="post" id="edit-form">

		<label><strong>Name</strong>
			<input type="text" id="edit-name" name="name" value="<?php echo $viewmodel['name']; ?>" />
		</label>

		<label><strong>Bio</strong>
			<textarea rows="5" id="edit-bio" name="bio" maxlength="250"><?php echo $viewmodel['bio']; ?></textarea>
		</label>

		
		<br />
		<div class="ui labeled icon button teal right fileUpload">
		  <i class="save icon"></i>
		  Save
		  <input type="submit" class="upload" />
		</div>
	</form>
	
						
		
	<a class="close-reveal-modal">&#215;</a>
</div>

<div class="reveal-modal small" id="change-pic-modal">
	<h3>Upload Profile Picture</h3>
	<div class="ui divider"></div>
	<form action="../changepic">
		
	</form>
	</div>
<script>
	
	$(function() {
		
		var profile_user_id = $('#profile-data-attr').data('user-id');
		
		$('#profile-controls .item').click(function(e) {
			e.preventDefault();
			
			
			if ( $(this).hasClass('active') ) 
				return false;
			
			var func = $(this).data('function');
			
			$('#profile-controls .item.active').removeClass('active').removeClass('teal').children('.label').removeClass('teal');
			$(this).addClass('active').addClass('teal').children('.label').addClass('teal');
			$('#profile-views').addClass('loading');
			$('#page-title').text($(this).data('title'));
			$('#profile-views').find('.profile-view.active').removeClass('active');
			
			if ( func == 'following' )
				load_following();
			else if ( func == 'followers' ) 
				load_followers();
			else if ( func == 'favorites' )
				load_favorites();
			else if ( func == 'posts' ) 
				load_posts();
			
		});
		
		function load_posts() {
			
			var url = 'http://social.mattaltepeter.com/users/getposts';
			var data = { 'user_id' : profile_user_id };
			
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(data) {
					console.log(data);
					var json = JSON.parse(data);
					
					if ( json.success ) {
						$('#data-posts-container').html(json.html);
						$('#profile-view-posts').addClass('active');
						$('#profile-views').removeClass('loading');
					}
				},
			});
			
		}
		
		function load_following() {
			
			var url = 'http://social.mattaltepeter.com/users/getfollowing';
			var data = { 'user_id' : profile_user_id };
			
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(data) {
					console.log(data);
					var json = JSON.parse(data);
					
					if ( json.success ) {
						$('#data-following-container').html(json.html);
						$('#profile-view-following').addClass('active');
						$('#profile-views').removeClass('loading');
					}
				},
			});
		}
		
		function load_followers() {
			
			var url = 'http://social.mattaltepeter.com/users/getfollowers';
			var data = { 'user_id' : profile_user_id };
			
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(data) {
					console.log(data);
					var json = JSON.parse(data);
					
					if ( json.success ) {
						$('#data-followers-container').html(json.html);
						$('#profile-view-followers').addClass('active');
						$('#profile-views').removeClass('loading');
					}
				},
			});
		}
		
		function load_favorites() {
			
			var url = 'http://social.mattaltepeter.com/users/getfavorites';
			var data = { 'user_id' : profile_user_id };

			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(data) {
					console.log(data);
					var json = JSON.parse(data);
					
					if ( json.success ) {
						$('#data-favorites-container').html(json.html);
						$('#profile-view-favorites').addClass('active');
						$('#profile-views').removeClass('loading');
					}
				},
			});
			
				
		}
		
		$('.edit-btn, .change-pic-btn')
		  .popup()
		;
		var files;
		
		$('#edit-form').submit(function(e) {
			e.preventDefault();
			var data = { 'name' : $('#edit-name').val(), 'bio' : $('#edit-bio').val() };
			$.post('../editprofile', data, function(result) {
				location.reload();
			});
		});
		
		$('#post-area').submit(function(e) {
			e.preventDefault();
			
			$('#post-form-area').addClass('loading');
			var formData = new FormData($(this)[0]);
			
		    $.ajax({
		        url: '../../posts/newPost',
		        type: 'POST',
		        data: formData,
		        async: true,
		        success: function (data) {
			        var json = JSON.parse(data);
		            console.log(data);
		            $('#post-form-area').removeClass('loading');
		            $('#post-body').val('');
		            $('#files').val('');
		            $('#attached').find('img').remove();
		            $('#attached').hide();
		            
		            if ( json.success ) {
			            var html;
			            var post_count = $('.comment').length;
			            var new_count = post_count + 1;
			            if ( post_count == 0 ) {
				            $('#data-posts-container').find('#no-post-msg').text('');
			            }

						
			            
			            if ( post_count > 0 ) {
				            html = json.html + '<div class="ui divider"></div>';
			            }
			            
			            else {
				            html = json.html;
			            }
			            
			            $('#data-posts-container').prepend(html);
			            $('#num_posts').text(new_count);
			            
		            }
		            //location.reload();
		            
		        },
		        cache: false,
		        contentType: false,
		        processData: false
		    });

			return false;
		});
		
		$('#new_profile_pic').change(function(e) {
			$('#profile-pic-loader').show();
			var form = new FormData($('#pic_form')); 
			form.append("image", $(this)[0].files[0]);
			$.ajax({
		        url: '../../users/changepic',
		        type: 'POST',
		        data: form,
		        async: true,
		        success: function (data) {
			        data = JSON.parse(data);
			        
		            console.log(data);

		            $('.ui.card .image').find('a').attr('data-featherlight', data.data.new_avatar);
		            $('.ui.card .image').find('img').attr('src', data.data.new_avatar);
		            $('.avatar').find('img').attr('src', data.data.new_avatar);
		            $('img.avatar.image').attr('src', data.data.new_avatar);
		            $('#profile-pic-loader').hide();
		        },
		        cache: false,
		        contentType: false,
		        processData: false
		    });
		});
		
		$('#files').change(function(e) {
			files = e.target.files;

	        var file = e.originalEvent.srcElement.files[0];
	
	        var img = document.createElement("img");
	        img.className = 'img-preview';
	        var reader = new FileReader();
	        reader.onloadend = function() {
	             img.src = reader.result;
	        }
	        reader.readAsDataURL(file);
	        $("#attached-files").html(img);
	        $("#attached").show();
	    	
		});		
		
		$('#delete-file').click(function() {
			$('#files').val('');
			$('#attached').find('img').remove();
			$('#attached').hide();
		});
		

		
		
	});
</script>