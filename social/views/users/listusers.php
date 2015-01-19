<div class="row" data-equalizer>
	<ul class="small-block-grid-1 medium-block-grid-3 large-block-grid-3">
	<?php 
		
		foreach( $viewmodel['data']->users as $user ) {
			$user_id = $user->id;
			if ( $_SESSION['user_id'] == $user_id ) 
				continue;
			$name = base64_decode($user->name);
			$screen_name = base64_decode($user->screen_name);
			$bio = $user->bio;
			$avatar = $user->avatar;
			
	?>
	<li>
			<div class="ui card" data-equalizer-watch>
				<div class="image">
					<a href="<?php echo HTTP; ?>users/profile/<?php echo $screen_name; ?>" style="width: 100%;"><img src="<?php echo $avatar; ?>" style="width: 100%;" /></a>
				</div>
			  <div class="content">
			    <a class="header left" id="profile-name" href="<?php echo HTTP; ?>users/profile/<?php echo $screen_name; ?>"><?php echo $name; ?></a>
				
			    <div style="clear:both"></div>
			    <div class="meta">
			      <span class="date"><?php echo $screen_name; ?></span>
			    </div>
			    <?php if ( is_following($user_id) ) {
				  
				?>
				<div class="ui labeled icon green button mini following-btn" style="margin-top: 10px; margin-bottom: 10px;" data-user-id="<?php echo $user_id; ?>">
				  <i class="checkmark icon"></i>
				  Following
				</div>
				<?php  
			    }
			    
			    else {
			    ?>
			    <div class="ui labeled icon teal button mini follow-btn" style="margin-top: 10px; margin-bottom: 10px;" data-user-id="<?php echo $user_id; ?>">
				  <i class="plus icon"></i>
				  Follow
				</div>
				<?php
					}
				?>
			    <div class="description" id="profile-bio">
			      <?php echo $bio; ?>
			    </div>

			  </div>
			</div>
	</li>
	<?php
		}
	?>
	</ul>
</div>

<script>
	$(document).foundation();
</script>
