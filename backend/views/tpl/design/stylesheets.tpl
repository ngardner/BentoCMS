<h1>Stylesheets</h1>

<div class="pad25">

	{include file="tpl/design/stylesheets_minimenu.tpl"}
	
	<ul class="item-title">
		<li class="col-first">Item Title</li>
	</ul>
	
	<div class="hug">
	{foreach from=$cssList item=stylesheet}
		<div class="item">
		
			<a href="{$httpUrl}admin/design/deletestylesheet?filename={$stylesheet}" onclick="return confirm('Are you sure you want to delete this stylesheet?')" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
			
			<div class="half-left">
				<a href="{$httpUrl}admin/design/editstylesheet?filename={$stylesheet}"><img src="{$skin}img/icon-css.gif" alt="{$layout.title}" class="icon" />{$stylesheet}.css</a>
			</div>
			
		<div class="clear"></div>
		</div>
	{foreachelse}
		- no stylesheets -
	{/foreach}
	</div>
	
</div>
