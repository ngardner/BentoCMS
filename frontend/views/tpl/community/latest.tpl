{include file=tpl/community/header.tpl}


	<div class="comm-cat-wrap">	
	<table width="100%" cellspacing="0">
	
		<thead>
			<td width="55%"><h5>Topic</h5></td>
			<td width="10%"><h5>Author</h5></td>
			<td width="10%"><h5>Replies</h5></td>
			<td width="25%"><h5>Last Reply</h5></td>
		</thead>
		{foreach from=$posts item=thread}
		<tr>
			<td width="55%"><a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.channelName}-{$thread.channelId}/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.categoryName}-{$thread.categoryId}/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.topic}-{$thread.id}-1.html">{$thread.topic}</a>
			{if $thread.sticky == 1}
			<img src="{$skin}img/sticky.png" width="18" height="18">
			{/if}
			{if $thread.locked == 1}
			<img src="{$skin}img/lock.png" width="11" height="18">
			{/if}
			</td>
			<td class="description" width="10%" align="center">{$thread.author}</td> 
			<td class="description" width="10%" align="center">{$thread.replies}</td>
			<td class="description" width="25%">{$thread.author} @ {$thread.cdate|date_format:"%m/%d/%Y %r"}</td>
		</tr>
	
	{/foreach}
	</table>
		
	</div>
	

</div>
</div>