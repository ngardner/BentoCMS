<h1>Blocks</h1>

<div class="pad25">

	{include file="tpl/content/blocks_minimenu.tpl"}
	
	<ul class="item-title">
		<li class="col-first">Item Title (identifier)</li>
	</ul>
	
	<div class="hug">
	{if !empty($blockList)}
			{foreach from=$blockList item=block}
			<div class="item" id="blockcontainer_{$block.id}">
			
				<a href="{$httpUrl}admin/content/deleteblock?block_id={$block.id}" onclick="return confirm('Are you sure you want to delete this block?');" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
				<a href="{$httpUrl}admin/content/cloneblock?block_id={$block.id}" class="icon-trash"><img src="{$skin}img/icon-clone.gif" alt="clone bahhh" title="clone bahhh"/></a>
			
				<div class="half-left">
					<a href="{$httpUrl}admin/content/editblock?block_id={$block.id}"><img src="{$skin}img/icon-block.gif" alt="{$page.title}" class="icon" title="delete"/>{$block.title}</a> <span class="small grey">({$block.keyName})</span>
				</div>
				
			<div class="clear"></div>
			</div>
			{/foreach}
	{else}
		- no blocks setup yet -
	{/if}
	</div>
	
</div>
