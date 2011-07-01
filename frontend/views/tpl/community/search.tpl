{include file=tpl/community/header.tpl}

<div class="pad20">

<h2>Search Results</h2>

{if $results}

	<table class="zebra" width="100%" cellspacing="0">
		<tr>
			<th class="first">Results</th>
			<th class="last" width="25%">Last Reply</th>
		</tr>
		{foreach from=$results item=result}
		<tr>
			<td><a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$result.channelName}-{$result.channelId}/{'/[^a-zA-Z]/'|preg_replace:'_':$result.categoryName}-{$result.categoryId}/{'/[^a-zA-Z]/'|preg_replace:'_':$result.topic}-{$result.postid}-1.html{if $result.replyId}#{$result.replyId}{/if}">{$result.topic}</a></td>
			<td class="description"><b>{$result.lastreply.author}</b><br/>{$result.lastreply.cdate|date_format:"%m/%d/%Y | %r"}</td>
		</tr>
		{/foreach}
	</table>

	{assign var="pageType" value='search'}
	{include file=tpl/community/pagination.tpl}
	
{else}
	No search results found.
{/if}

</div><!-- pad -->

</div>
</div>