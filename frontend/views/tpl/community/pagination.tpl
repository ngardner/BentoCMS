{if $pageType == 'viewThread'}

<div class="pages">
	
	Page(s): 
		<select name="pages" id="pageSelector" onchange="selectPage(this);">
				{section name=pages loop=$totalPages}
					<option value="{$smarty.section.pages.iteration}" {if $smarty.section.pages.iteration == $pageNumb}selected{/if} url="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.topic.topic}-{$thread.topic.id}-{$smarty.section.pages.iteration}.html">{$smarty.section.pages.iteration}</option>
				{/section}
		</select>
		of {$totalPages}
	{if $pageNumb > 1}<a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.topic.topic}-{$thread.topic.id}-{$pageNumb-1}.html">Prev</a>{/if}
	{if $totalPages > $pageNumb}<a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$thread.topic.topic}-{$thread.topic.id}-{$pageNumb+1}.html">Next</a>{/if}
</div><!-- paginate -->

{elseif $pageType == 'viewCategory'}

<div class="pages comm-pages">
	
	Page(s): 
		<select name="pages" id="pageSelector" onchange="selectPage();">
				{section name=pages loop=$totalPages}
					<option value="{$smarty.section.pages.iteration}" {if $smarty.section.pages.iteration == $pageNumb}selected{/if} url="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}-{$smarty.section.pages.iteration}.html">{$smarty.section.pages.iteration}</option>
				{/section}
		</select>
		of {$totalPages}
		{if $pageNumb > 1}<a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}-{$pageNumb-1}.html">Prev</a>{/if}
	{if $totalPages > $pageNumb}<a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}-{$pageNumb+1}.html">Next</a>{/if}
</div><!-- paginate -->

{elseif $pageType == 'search'}

<div class="pages comm-pages">
	{if $pageNumb > 1}<a href="{$httpUrl}community/search.html?pageNumb={$pageNumb-1}&search={$search}">Prev</a>{/if}
	Page(s): 
		<select name="pages" id="pageSelector" onchange="selectPage();">
				{section name=pages loop=$totalPages}
					<option value="{$smarty.section.pages.iteration}" {if $smarty.section.pages.iteration == $pageNumb}selected{/if} url="{$httpUrl}community/search.html?pageNumb={$smarty.section.pages.iteration}&search={$search}">{$smarty.section.pages.iteration}</option>
				{/section}
		</select>
		of {$totalPages}
	{if $totalPages > $pageNumb}<a href="{$httpUrl}community/search.html?pageNumb={$pageNumb+1}&search={$search}">Next</a>{/if}
</div><!-- paginate -->

{/if}


<div class="clear"></div>