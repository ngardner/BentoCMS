<h1>Create Permissions</h1>

<div class="pad25">
	
	<span class="small">
		Below is a list of all possible permissions that are not currently setup.<br/>
		Check the "create" box, and enter a name/description to create the permission rule.
	</span>
	
	{if $possiblePermissions}
	<form method="post" action="{$httpUrl}admin/administration/createpermissions">
	<table width="100%" class="table zebra">
		<tr>
			<th>Create</th>
			<th>Permission</th>
			<th>Name</th>
			<th>Description</th>
		</tr>
		{foreach from=$possiblePermissions item=permission name=permissions}
		<tr>
			<td><input type="checkbox" name="permission[{$smarty.foreach.permissions.iteration}][enable]" value="1"/></td>
			<td>
				<input type="hidden" name="permission[{$smarty.foreach.permissions.iteration}][controller]" value="{$permission.controller}"/>
				<input type="hidden" name="permission[{$smarty.foreach.permissions.iteration}][action]" value="{$permission.action}"/>
				{$permission.controller} / {$permission.action}
			</td>
			<td><input type="text" name="permission[{$smarty.foreach.permissions.iteration}][name]"/></td>
			<td><input type="text" name="permission[{$smarty.foreach.permissions.iteration}][description]"/></td>
		</tr>
		{/foreach}
	</table>
	<input type="hidden" name="dosubmit" value="1"/>
	<input type="submit" value="Save" class="btn"/>
	</form>
	{else}
		
		<p>All possible permissions are already created.</p>
		
	{/if}
	
	<br/>

</div>
