{if !$depth}
	{assign var=depth value=0}
{/if}

{foreach from=$pageList item=page}
	
	{if $page.id != $pageInfo.id}
		
		<option value="{$page.id}" {if $pageInfo.parent_id == $page.id}selected="selected"{/if}>
			
			{section name='spacer' loop=$depth}
				-
			{/section}
			
			{$page.title}
		</option>
		
		{if !empty($page.children)}
			
			{assign var=depth value=`$depth+1`}
			{assign var=pageList value=$page.children}
			{include file="tpl/content/pages_list_select.tpl"}
			{assign var=depth value=`$depth-1`}
			
		{/if}
		
		
		
	{/if}
	
{/foreach}
