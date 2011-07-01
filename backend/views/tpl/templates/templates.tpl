<h1>{$group} Templates</h1>

<div class="pad25">

	<ul class="item-title">
		<li class="col-first">Name</li>
	</ul>
	
	<div class="hug">
	{if !empty($templateList)}
			{foreach from=$templateList item=template}
			<div class="item" id="blockcontainer_{$template.id}">
				
				<div class="half-left">
					<a href="{$httpUrl}admin/template/edittemplate?template_id={$template.id}&amp;group={$group}">{$template.name}</a>
				</div>
				
			<div class="clear"></div>
			</div>
			{/foreach}
	{else}
		- no templates setup yet -
	{/if}
	</div>
	
</div>
