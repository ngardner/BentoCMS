<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>{$ProductTitle} Admin</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="{$bento}/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="{$skin}css/login.css"/>
</head>
<body>
	
	<div id="container">
		
		<div id="login-box">
		
		<h1>account login</h1>
		
			<div class="pad25 clear">
				
				{if $showLogout == 1}
					<p><b>You are now logged out, see ya soon!</b></p>
				{/if}
				
				{if $errorMsg}
					<div class="error">{$errorMsg}</div>
				{/if}
				
				<form method="post" action="{$httpUrl}admin/login/login">
				
				<p>
					<label>email address</label><br/>
					<input type="text" name="email" class="text" tabindex="1"/>
				</p>
				
				<p>
					<label>password</label> <a href="{$httpUrl}user/forgotpassword">Forgot Password?</a><br/>
					<input type="password" name="password" class="text" tabindex="2"/>
				</p>
				
				<input type="submit" value="login" class="btn med a-right" tabindex="3"/>
				
				<div class="checkbox">
					<label for="rememberme">remember me</label> <input type="checkbox" id="rememberme"/>
				</div>
				
				</form>
				
				<div class="clear"></div>
				
			</div>
		</div><!-- login-box -->
		
	</div><!-- container -->
	
</body>
</html>
