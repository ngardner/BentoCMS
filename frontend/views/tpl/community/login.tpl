{include file=tpl/community/header.tpl}

<div class="pad20">


{if $loggedOut}

<br/><br/><br/>

<center>
<h2>You are now logged out, see ya soon!</h2>
<span><a href="{$httpUrl}community/login.html">Click here to login</a></span>
</center>

<br/><br/><br/>

{else}

<div class="halfsies sep">

{if $errorMessages}
	{foreach from=$errorMessages item=error}
		<div class="error">{$error}</div>
	{/foreach}
{/if}

{if $created == false}
	<h5>New User Registration</h5><br/>
	Wanna get involved with the Community? Sign up below!<br/><br/><br />
	
	<form method="post" action="{$httpUrl}community/login.html" autocomplete="off">
		<input type="hidden" name="createAccount" value="1"/>
				<label>Email Address *</label><br /><input type="text" name="create_email" class="text small"/><br /><br />
				<label>Community Display Name *</label><br /><input type="text" name="create_displayName" class="text small"/><br /><br />
				<label>Password *</label><br /><input type="password" name="create_password" class="text small"/><br /><br />
				<label>Confirm Password *</label><br /><input type="password" name="create_password2" class="text small"/><br /><br />
				<input type="checkbox" name="create_terms" target="_blank"/> I agree with the <a href="/terms-of-use.html">Terms of Use</a><br /><br />
				<input type="image" src="{$skin}img/btn-create-account.png"/>
	</form>
{else}
	<h5>Welcome to the Community!</h5><br/>
	<div class="success">Your Retail Roar Community account has been created.<br/>
	Please login to the right &raquo;</div>
{/if}

</div><!-- halfsies -->

<div class="halfsies">

<h5>Existing User Login</h5><br/>
Already have a Community account? Log in below<br /><br /><br />

		{if $errorMsg}
			<div class="errormsg">{$errorMsg}</div>
		{/if}
		
		<form method="post" action="{$httpUrl}community/login.html">
			<input type="hidden" name="login" value="1">
				<label>Email Address</label><br/> <input type="text" name="email" class="text small" value=""/><br /><br />
				<label>Password</label><br/> <input type="password" name="password" class="text small" value=""/><br /><br />
				<input type="image" src="{$skin}img/btn-login.jpg"/>
		</form>
		
</div><!-- halfsies -->
		
<div class="clear"></div>



{/if}

</div><!-- pad -->
</div>
</div>
