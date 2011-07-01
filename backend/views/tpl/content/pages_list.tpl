<div class="sortable" saveurl="{$httpUrl}admin/content/savepageorder">
{foreach from=$pageList item=page}
	<div class="item{if !empty($page.children)}-parent{/if} sort{if !empty($page.children)}-parent{/if}" id="page_{$page.id}">
		
		<a href="{$httpUrl}admin/content/deletepage?page_id={$page.id}" onclick="return confirm('Are you sure you want to delete this page?')" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete" title="delete"/></a>
		<a href="{$httpUrl}admin/content/clonepage?page_id={$page.id}" class="icon-trash"><img src="{$skin}img/icon-clone.gif" alt="clone bahhh" title="clone bahhh"/></a>
		
		<div class="half-left">
			
			{if $page.type == 'link'}
				
				<img src="{$skin}img/icon-link.gif" alt="{$page.title}" class="icon" />
				{if !empty($page.children)}
					<span onclick="showhideChildren('{$page.id}');" class="pop" id="expandbutton_{$page.id}"><img src="{$skin}img/icon-plus.gif" alt="expand"/></span>
				{/if}
				<a href="{$httpUrl}admin/content/editpage?page_id={$page.id}&amp;type=link">
				{$page.title}
				</a>
				
				<span class="small grey">({$page.url})</span>
				
			{else}
				
				<img src="{$skin}img/icon-page.gif" alt="{$page.title}" class="icon" />
				{if !empty($page.children)}
					<span onclick="showhideChildren('{$page.id}');" class="pop" id="expandbutton_{$page.id}"><img src="{$skin}img/icon-plus.gif" alt="expand"/></span>
				{/if}
				<a href="{$httpUrl}admin/content/editpage?page_id={$page.id}">
				{$page.title}
				</a>
				
				<span class="small grey">({$page.keyName})</span>
				
			{/if}
			
		</div>
		
		
		<div class="col">{$page.status}</div>
		<div class="col">{$page.template}</div>
		
		{if !empty($page.children)}
			{assign var=pageList value=$page.children}
			<div class="spacer clear"> </div>
			<div id="childrenPages_{$page.id}" style="display: none;">
				{include file="tpl/content/pages_list.tpl"}
			</div>
		{/if}
		
		<div class="clear"></div>
		
	</div>
{/foreach}
</div>
