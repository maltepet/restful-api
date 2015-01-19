<div class="row">
	<div class="large-6 large-offset-3 columns">
		<?php
			if ( isset($_SESSION['error']) ) {
				if ( $_SESSION['error'] != '' ) 
				echo "<div class='alert-box alert'>{$_SESSION['error']}</div>";
			}
			
		?>
		
		<h3>Login</h3>
		<div class="panel">		
			<form action="signin" method="post">
				<label>Email
					<input type="text" name="email" id="email" />
				</label>
				
								
				<label>Password
					<input type="password" name="password" id="password" />
				</label>
				<div class="ui button teal labeled icon tiny fileUpload right">
					<i class="unlock alternate icon"></i>
					Login
					<input type="submit" value="Login" name="login_sub" class="upload" />
				</div>
			</form>
			
			<div style="clear:both"></div>
		</div>
	</div>
</div>