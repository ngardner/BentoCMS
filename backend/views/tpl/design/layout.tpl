<script type="text/javascript" src="{$skin}js/layout.js"></script>

<h1>{if $pageInfo}Edit{else}Creating{/if} Layout</h1>

<div class="pad25">

	{include file="tpl/design/layouts_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/design/editlayout" id="layoutForm">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="layout_id" value="{$layoutInfo.id}"/>
		
		<div id="tabs">
			<div class="bar">
				<ul>
					<li><a href="#layoutTitle" onclick="return false;">Layout Title</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="layoutTitle">
				<input type="text" name="layout_title" value="{$layoutInfo.title}" class="text"/><br>
			</div>
			
		</div><!-- tabs-->
		
		<div id="tabs2">
			<div class="bar">
				<ul>
					<li><a href="#layoutHTML" onclick="return false;">HTML</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="layoutHTML">
				<textarea name="layout_code" class="textarea code">{$layoutInfo.code}</textarea>
			</div>
			
		</div><!-- tabs-->
		
		<input type="submit" name="submit" value="Save" class="btn sml" onclick="ajaxSaveLayout(); return false;"/>
		<input type="submit" name="submit" value="Save and Close" class="btn med" />
		
	</form>
	
</div>
