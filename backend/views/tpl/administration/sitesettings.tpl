<h1>Site Settings</h1>

<div class="pad25">
	
	<form method="post" action="{$httpUrl}admin/sitesettings/index">
	<input type="hidden" name="dosave" value="1"/>

	{assign var=group value=''}
	
	{foreach from=$siteSettings item=siteSetting}
		
		{if $siteSetting.group != $group}
			
			<h5>{$siteSetting.group} Settings</h5><br/>
			{assign var=group value=$siteSetting.group}
			
		{/if}
		
		<div class="pad10">
		<label>{$siteSetting.name}</label>
		{if $siteSetting.values}
			<select name="sitesettings[{$siteSetting.id}]" class="select">
				{assign var=values value=','|explode:$siteSetting.values}
				{foreach from=$values item=value}
					<option value="{$value}" {if $value == $siteSetting.value}selected="selected"{/if}>{$value}</option>
				{/foreach}
			</select>
		{else}
			<input type="text" name="sitesettings[{$siteSetting.id}]" value="{$siteSetting.value}" class="text"/>
		{/if}
		<span class="small">{$siteSetting.description}</span>
		</div>
		
	{/foreach}
	
	<div class="btns clear">
		<input type="submit" name="submit" value="Save" class="btn sml"/>
	</div>
	
	</form>
	
</div>
