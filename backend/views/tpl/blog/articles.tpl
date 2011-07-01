<h1>Articles</h1>

<div class="pad25">

	{include file="tpl/blog/articles_minimenu.tpl"}
	
	View Category
	<select onchange="window.location='{$httpUrl}admin/blog/articles?category_id=' + $(this).val();">
		<option value="" {if empty($currentCategory)}selected="selected"{/if}>- view all -</option>
		{include file="tpl/blog/categories_list_select.tpl"}
	</select>
	
	<ul class="item-title">
		<li class="col-last">Status</li>
		<li class="col">Category</li>
		<li class="col-first">Item Title (identifier)</li>
	</ul>
	
	<div class="hug">
	{if !empty($articleList)}
			{foreach from=$articleList item=article}
			<div class="item">
			
				<a href="{$httpUrl}admin/blog/deletearticle?article_id={$article.id}" onclick="return confirm('Are you sure you want to delete this article?');" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
				
				<div class="half-left">
					<a href="{$httpUrl}admin/blog/editarticle?article_id={$article.id}">{$article.title|truncate:60}</a>
				</div>
				
				<div class="col">{$article.status}</div>
				<div class="col">{$article.category|truncate:25}</div>
				
			<div class="clear"></div>
			</div>
			{/foreach}
	{else}
		- no articles setup yet -
	{/if}
	</div>

	<div class="pages">
		{if $pageNumb > 1}<a href="{$httpUrl}admin/blog/articles?pageNumb={$pageNumb-1}&amp;category_id={$currentCategory}">Prev</a>{/if}
		Page(s): 
			<select name="pages" onchange="document.location = '{$httpUrl}/admin/blog/articles?pageNumb=' + $(this).val() + '&amp;category_id={$currentCategory}';">
					{section name=pages loop=$totalPages}
						<option value="{$smarty.section.pages.iteration}" {if $smarty.section.pages.iteration == $pageNumb}selected{/if}>{$smarty.section.pages.iteration}</option>
					{/section}
			</select>
			&nbsp; of &nbsp; {$totalPages}
		{if $totalPages > $pageNumb}<a href="{$httpUrl}admin/blog/articles?pageNumb={$pageNumb+1}&amp;category_id={$currentCategory}">Next</a>{/if}
	</div><!-- paginate -->
	
</div>
