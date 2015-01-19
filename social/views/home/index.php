<?php 
	
	if ( isset($_SESSION['logged_in']) ) {
		$new_url = HTTP . 'users/profile/' . $_SESSION['screen_name'];
		header("Location: {$new_url}");
	}
?>
<div class="row">
	<div class="large-12 columns">
		<?php
			if ( isset($_SESSION['error']) ) {
				if ( $_SESSION['error'] != '' ) 
				echo "<div class='alert-box alert'>{$_SESSION['error']}</div>";
			}
		?>
	</div>
</div>
<div class="row">
	<div class="large-12 columns">
		<h3 class="ui top attached header">Sign up</h3>
		<div class="ui attached segment">

				
				<form method="post" action="<?php echo HTTP; ?>home/register" id="register-form">
						<div class="alert-box alert" style="display:none" id="register-error">There are problems that need to be fixed!</div>
						<div class="row">
							<div class="large-6 columns">
								<label>First Name
									<input type="text" name="first" id="first" placeholder="First Name" />
								</label>
							</div>
							
							<div class="large-6 columns">
								<label>Last Name
									<input type="text" name="last" id="last" placeholder="Last Name" />
								</label>
							</div>
						</div>
						
						<label>Email
							<input type="text" name="email" id="email" placeholder="Email" />
							<small class="error" id="email-err" style="display:none;">Email already exists!</small>
						</label>
						
						<label>Screen Name
							<input type="text" name="screen_name" id="screen_name" placeholder="Screen Name" />
							<small class="error" id="screen-err" style="display:none;">Screen name already exists!</small>
						</label>
						
						<label>Password
							<input type="password" name="password" id="password" placeholder="Password"/>
						</label>
						
						<div class="ui button teal labeled icon tiny fileUpload right">
							<i class="edit icon"></i>
							Register
							<input type="submit" name="reg_sub" value="Register" class="upload" />
						</div>
						<div style="clear:both"></div>
					</form>
				</div>
			</div>
		</div>
		

</div>


<script>
	
	$(function() {
		var email_err = false;
		var sn_err = false;
		
		$('#register-form #email').blur(function() {
			if ( $(this).val() != '' ) {
				var data = { 'email' : $(this).val() };
				
				$.post('http://social.mattaltepeter.com/home/checkemail', data, function(result) {
					var data = JSON.parse(result);
					if ( data.data ) {
						$('#register-form #email').addClass('error');
						$('#email-err').removeClass('success').text('Email already exists!').show();
						
						email_err = true;
					}
					
					else {
						$('#register-form #email').addClass('error');
						$('#email-err').addClass('success').text('Email is available!').show();
						
						email_err = false;
					}	
				});
			}
		});
		
		$('#screen_name').blur(function() {
			if ( $(this).val() != '' ) {
				var data = { 'screen_name' : $(this).val() };
				
				$.post('http://social.mattaltepeter.com/home/checksn', data, function(result) {
					var data = JSON.parse(result);
					if ( data.data ) {
						$('#screen_name').addClass('error');
						$('#screen-err').removeClass('success').text('Screen name already exists!').show();
						
						sn_err = true;
					}
					
					else {
						$('#screen_name').addClass('error');
						$('#screen-err').addClass('success').text('Screen name is available!').show();
						
						sn_err = false;
					}	
				});
			}
		});
		
		$('#register-form').submit(function() {
			if ( email_err || sn_err ) {
				$('#register-error').show();
				return false;
			}
		});
	});
	
</script>