{include file=tpl/community/header.tpl}

<div class="pad20">

	<div class="action">
	<a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}/newpost.html" class="butt">+ Create New Topic</a>
	</div>
	
	<span>
		<a href="{$httpUrl}community/index.html">&laquo; back to Community</a>
	</span><br/><br/>
	
	<h2>{$category.name}</h2>
	
	{if $threads}
	
		<table class="zebra" width="100%" cellspacing="0">
			<tr>
				<th class="first" width="55%">Topic</th>
				<th width="50">Author</th>
				<th width="50" style="text-align: center;">Replies</th>
				<th class="last" width="25%">Last Reply</th>
			</tr>
			{foreach from=$threads item=thread}
			<tr>
				<td>
					<a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.channelName}-{$thread.channelId}/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.categoryName}-{$thread.categoryId}/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.topic}-{$thread.id}-1.html" class="parent">{$thread.topic}</a>
					{if $thread.sticky == 1}
					<img src="{$skin}img/icon/icon-sticky.png">
					{/if}
					{if $thread.locked == 1}
					<img src="{$skin}img/icon/icon-lock.png">
					{/if}
				</td>
				<td class="description"><b>{$thread.author}</b></td> 
				<td class="description" align="center">{$thread.replies}</td>
				<td class="description"><b>{$thread.lastreply.author}</b><br/> {$thread.lastreply.cdate|date_format:"%m/%d/%Y | %r"}</td>
			</tr>
		
		{/foreach}
		</table>
			
	
	
		{assign var="pageType" value='viewCategory'}
		{include file=tpl/community/pagination.tpl}
		
	{else}
		No threads in this category yet.
	{/if}

</div><!-- pad -->

</div>
</div>