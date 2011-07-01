<script type="text/javascript" src="{$skin}js/contentpages.js"></script>

<h1>Pages</h1>

<div class="pad25">

	{include file="tpl/content/pages_minimenu.tpl"}
	
	<div class="small hand">
		<span onclick="expandAllPages();"><img src="{$skin}img/icon-plus.gif" alt="+"/> Expand all</span>
		<span onclick="collapseAllPages();"><img src="{$skin}img/icon-minus.gif" alt="-"/> Collapse all</span>
	</div>
	
	<ul class="item-title">
		<li class="col-last clone">Status</li>
		<li class="col">Layout</li>
		<li class="col-first">Item Title (identifer)</li>
	</ul>
	
	{include file="tpl/content/pages_list.tpl"}
	
</div>