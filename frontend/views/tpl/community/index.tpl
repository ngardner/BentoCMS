{include file=tpl/community/header.tpl}

<div class="pad20">

<h2>Welcome to the Community</h2>

<table width="100%" class="zebra" cellspacing="0">
{foreach from=$channels item=channel}
	<tr>
		<th class="first">{$channel.name}</th>
		<th class="last" width="50">Threads</th>
	</tr>
	
	{foreach from=$categories[$channel.id] item=category}
		{if $category.channelid == $channel.id}
		<tr>
			<td><a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}-1.html" class="parent">{$category.name}</a><br/>
			<i class="description">{$category.description}</i></td>
			<td class="description" align="center">{$category.threads}</td>
		</tr>
		{/if}
	{/foreach}
	
{/foreach}
</table>

</div><!-- pad -->

</div>
</div>
