<h1>Layouts</h1>

<div class="pad25">

	{include file="tpl/design/layouts_minimenu.tpl"}
	
	<ul class="item-title">
		<li class="col-first">Item Title</li>
	</ul>
	
	<div class="hug">
	{foreach from=$layoutList item=layout}
		<div class="item">
		
			<a href="{$httpUrl}admin/design/deletelayout?layout_id={$layout.id}" onclick="return confirm('Are you sure you want to delete this layout?')" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
			
			<div class="half-left">
				<a href="{$httpUrl}admin/design/editlayout?layout_id={$layout.id}"><img src="{$skin}img/icon-layout.gif" alt="{$layout.title}" class="icon"  class="icon"/>{$layout.title}</a>
			</div>
		
		<div class="clear"></div>
		</div>
	{/foreach}
	</div>
	
</div>
