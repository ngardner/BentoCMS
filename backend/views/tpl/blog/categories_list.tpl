<div class="sortable" saveurl="{$httpUrl}admin/blog/savecategoryorder">
{foreach from=$categoryList item=category}
	<div class="item{if !empty($category.children)}-parent{/if} sort{if !empty($category.children)}-parent{/if}" id="category_{$category.id}">
	
		<a href="{$httpUrl}admin/blog/deletecategory?category_id={$category.id}" onclick="return confirm('Are you sure you want to delete this category?')" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
	
		<div class="half-left">
			<a href="{$httpUrl}admin/blog/editcategory?category_id={$category.id}"><img src="{$skin}img/icon-page.gif" alt="{$category.title}" class="icon" />{$category.title}</a> <span class="small grey">({$category.keyName})</span>
		</div>
		
		<div class="col"><a href="{$httpUrl}admin/blog/articles?category_id={$category.id}">{$category.article_count}</a></div>
		
		{if !empty($category.children)}
			{assign var=categoryList value=$category.children}
			<div class="spacer"></div>
			{include file="tpl/blog/categories_list.tpl"}
		{/if}
	<div class="clear"></div>
	</div>
{/foreach}
</div>
