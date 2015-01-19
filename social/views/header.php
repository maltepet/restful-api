<!DOCTYPE html>

<html>
	<head>
		<title>Social Network | <?php echo $viewmodel['page_title']; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="<?php echo HTTP; ?>css/normalize.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo HTTP; ?>css/foundation.min.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP; ?>dist/semantic.min.css">
		<link href="//cdn.rawgit.com/noelboss/featherlight/1.0.3/release/featherlight.min.css" type="text/css" rel="stylesheet" title="Featherlight Styles" />
		<link rel="stylesheet" href="<?php echo HTTP; ?>css/style.css" type="text/css" />

		<script src="<?php echo HTTP; ?>js/vendor/modernizr.js"></script>
		<script src="<?php echo HTTP; ?>js/vendor/jquery.js"></script>
		<script src="//cdn.rawgit.com/noelboss/featherlight/1.0.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo HTTP; ?>dist/semantic.min.js"></script>
		<script src="<?php echo HTTP; ?>js/foundation.min.js"></script>
		<script src="<?php echo HTTP; ?>js/app.js"></script>
		
	</head>
	
	<body>
	
	<div class="contain-to-grid">
		<nav class="top-bar" data-topbar role="navigation" style="margin-bottom: 50px;">
		  <ul class="title-area">
		    <li class="name">
		      <h1><a href="#">Social Network</a></h1>
		    </li>
		    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
		  </ul>
		  <section class="top-bar-section">
		  <ul class="right">
		  	<?php
			  	
			  	if ( isset($_SESSION['logged_in']) ) {
				  	if ( $_SESSION['logged_in'] ) {
					  	
			?>
					<li><a href="<?php echo HTTP; ?>users/listusers">Find People</a></li>
					<li class="divider"></li>
					<li class="has-dropdown">
				        <a href="#"><img class="ui avatar image" src="<?php echo $_SESSION['avatar']; ?>" /><?php echo $_SESSION['screen_name']; ?></a>
				        <ul class="dropdown">
					      <li><a href="<?php echo HTTP; ?>users/profile/<?php echo $_SESSION['screen_name']; ?>"><i class="large user icon"></i> Profile</a></li>
				          <li class="divider"></li>
				          <li><a href="<?php echo HTTP; ?>home/logout"><i class="large sign out icon"></i> Logout</a></li>
				        </ul>
				      </li>
			<?php
					}
			  	}
			  	
			  	else {
			?>
					<li><a href="<?php echo HTTP; ?>">Register</a></li>
					<li class="active teal"><a href="#" data-reveal-id="login-modal">Login</a></li>
			<?php
			  	}
			?>
		  </ul>
		  </section>
		</nav>
	</div>
		<div id="login-modal" class="reveal-modal tiny" data-reveal>
			<h3>Login</h3>
			<form action="home/signin" method="post">
				<label for="username">Email
					<input type="text" name="email" id="email" />
				</label>
				
				<label for="password">Password
					<input type="password" name="password" id="password" />
				</label>
				
				<div class="ui button teal labeled icon tiny fileUpload right">
					<i class="unlock alternate icon"></i>
					Login
					<input type="submit" value="Login" name="login_sub" class="upload" />
				</div>
			</form>
			<a class="close-reveal-modal">&#215;</a>
		</div>

		<?php require $viewloc; ?>
		
		
		<script>
			$(document).foundation();
		</script>