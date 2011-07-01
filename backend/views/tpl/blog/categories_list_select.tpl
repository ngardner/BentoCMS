{foreach from=$categoryList item=category}
	
	{if $category.id != $categoryInfo.id}
		
		<option value="{$category.id}" {if $categoryInfo.parent_id == $category.id}selected="selected"{/if}{if $articleInfo.category_id == $category.id}selected="selected"{/if}{if $currentCategory == $category.id}selected="selected"{/if}>
			
			{section name='spacer' loop=$depth}
				-
			{/section}
			
			{$category.title}
		</option>
		
		{if !empty($category.children)}
			
			{assign var=depth value=`$depth+1`}
			{assign var=categoryList value=$category.children}
			{include file="tpl/blog/categories_list_select.tpl"}
			{assign var=depth value=`$depth-1`}
			
		{/if}
		
		
		
	{/if}
	
{/foreach}
