{include file=tpl/community/header.tpl}

{if $successMessage}
<div class="message success">
	{$successMessage}
</div>
{/if}
{if $errorMessages}
	{foreach from=$errorMessages item=error}
		<div class="errormsg">{$error}</div>
	{/foreach}
{/if}

<div class="pad20">

	<form method="post" action="{$httpUrl}community/account.html" autocomplete="off">
	<input type="hidden" name="manageAccount" value="1"/>
	
	<table width="100%" cellspacing="0">
		<tr>
			<th colspan="2" class="first last">Manage Account</th>
		</tr>
		<tr>
			<td width="200"><label>Email Address</label></td>
			<td>{$userInfo.email}<input type="hidden" name="user_email" value="{$userInfo.email}"></td>
		</tr>
		<tr>
			<td><label>Forum Display Name</label></td>
			<td>{$userInfo.displayName}</td>
		</tr>
		<tr>
			<td><label>Change Password</label></td>
			<td><input type="password" name="user_password" class="text small"/></td>
		</tr>
		<tr>
			<td><label>Confirm Password</label></td>
			<td><input type="password" name="user_password2" class="text small"/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="image" src="{$skin}img/btn-change-password.png"/></td>
		</tr>
	</table>

	</form>

</div><!-- pad -->

</div>
</div>
