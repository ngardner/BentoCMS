<h1>{$userType} Users</h1>

<div class="pad25">

	{include file="tpl/administration/users_minimenu.tpl"}
	
	<ul class="item-title">
		<li class="col-last">Last Login</li>
		<li class="col">Title</li>
		<li class="col-first">Name</li>
	</ul>
	
	<div class="hug">
	{if !empty($userList)}
			{foreach from=$userList item=user}
			<div class="item" id="usercontainer_{$user.id}">
			
				<a href="{$httpUrl}admin/users/deleteuser?user_id={$user.id}" onclick="return confirm('Are you sure you want to delete this user?');" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
				
				<div class="left">
				<a href="{$httpUrl}admin/users/edituser?user_id={$user.id}">{$user.fName} {$user.lName}</a> <span class="small">({$user.email})</span>
				</div>

				<div class="col">{$user.lastLogin|date_format:"%D"}</div>
				<div class="col">{$user.title}</div>
			
			<div class="clear"></div>
			</div>
			{/foreach}
	{else}
		- no users yet -
	{/if}
	</div>
	
</div>
