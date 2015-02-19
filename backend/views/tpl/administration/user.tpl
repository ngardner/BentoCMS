<script type="text/javascript" src="{$skin}js/user.js"></script>

<h1>{if $userInfo}Edit{else}Creating{/if} User</h1>

<div class="pad25">

	{include file="tpl/administration/users_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/users/edituser">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="user_id" value="{$userInfo.id}"/>
		
		<div id="tabs">
			<div class="bar">
				<ul>
					<li><a href="#userInfo" onclick="return false;">Information</a></li>
					<li><a href="#admin_permissions" onclick="return false;">Permissions</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="userInfo">
			
				<label>User Type</label><br/>
				<select name="user_type" onchange="togglePermissions();" class="select">
					<option value="user" {if $userInfo.type == 'user'}selected="selected"{/if}>Normal</option>
					<option value="admin" {if $userInfo.type == 'admin'}selected="selected"{/if}>Administrator</option>
				</select><br/><br/>

				<label>First Name</label><br/>
				<input type="text" name="user_fname" value="{$userInfo.fName}" class="text"/><br/><br/>
				
				<label>Last Name</label><br/>
				<input type="text" name="user_lname" value="{$userInfo.lName}" class="text"/><br/><br/>
				
				<label>Title</label><br/>
				<input type="text" name="user_title" value="{$userInfo.title}" class="text"/><br/><br/>
				
				<label>Email</label><br/>
				<input type="text" name="user_email" value="{$userInfo.email}" class="text" autocomplete="off"/><br/><br/>
				
				{if $userInfo.id}
					
					<label>Change Password</label><br/>
					
				{else}
					
					<label>Password</label><br/>
					
				{/if}
				
				<input type="password" name="user_password" value=""  autocomplete="off" class="text"/><br/><br/>
				
				<label>Confirm Password</label><br/>
				<input type="password" name="user_password2" value=""  autocomplete="off" class="text"/><br/><br/>

				
			</div>
			
			{if $userInfo.type == 'admin'}
			<div class="block_content tab_content" id="admin_permissions">
				
				<div class="white">
					<div class="pad10">
						{if $userInfo.id == $UserInfo.id}
							<p>You cannot edit your own permissions</p>
							{foreach from=$permissions item=permission}
								{if $permission.hasPermission}
									<input type="hidden" name="user_permissions[]" value="{$permission.id}"/>
								{/if}
							{/foreach}
						{else}
							<ul class="permissions-list">
							{foreach from=$permissions item=permission}
								<li><input type="checkbox" name="user_permissions[]" value="{$permission.id}" {if $permission.hasPermission}checked="checked"{/if}> <b>{$permission.name}</b> - <span class="small">{$permission.description}</span></li>
							{/foreach}
							</ul>
						{/if}
					</div>
				</div>
				
			</div>
			{/if}
			
		</div><!-- tab-->

		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml" onclick="return validUserForm();"/>
			<input type="submit" name="submit" value="Save and Close" class="btn med"  onclick="return validUserForm();"/>
		</div>
		
	</form>
		
</div>
