<h1>Slideshows</h1>

<div class="pad25">

	{include file="tpl/slideshows/slideshow_minimenu.tpl"}
	
	<ul class="item-title">
		<li class="col-first">Slideshow</li>
	</ul>
	
	<div class="hug">
	{if !empty($slideshowList)}
			{foreach from=$slideshowList item=slideshow}
			<div class="item">
			
				<a href="{$httpUrl}admin/slideshows/deleteshow?show_id={$slideshow.id}" onclick="return confirm('Are you sure you want to delete this slideshow?');" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
			
				<div class="half-left">
					<a href="{$httpUrl}admin/slideshows/editshow?show_id={$slideshow.id}">
						{$slideshow.title} <span class="small grey">({$slideshow.keyName})</span>
					</a>
				</div>
			
			<div class="clear"></div>
			</div>
			{/foreach}
	{else}
		- no slideshows setup yet -
	{/if}
	</div>
	
</div>
