<script type="text/javascript" src="{$skin}js/stylesheet.js"></script>

<h1>{if $pageInfo}Edit{else}Creating{/if} Stylesheet</h1>

<div class="pad25">

	{include file="tpl/design/stylesheets_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/design/editstylesheet" id="stylesheetForm">
		
		<input type="hidden" name="dosave" value="1"/>
		
		<div id="tabs">
			<div class="bar">
				<ul>
					<li><a href="#stylesheetTitle" onclick="return false;">Stylesheet Title</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="stylesheetTitle">
				<input type="text" name="css_filename" value="{$filename}" class="text med"/> .css
			</div>
			
		</div><!-- tabs -->
		
		<div id="tabs2">
			<div class="bar">
				<ul>
					<li><a href="#stylesheetCSS" onclick="return false;">CSS Lovin'</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="stylesheetCSS">
				<textarea name="css_content" class="textarea code">{$stylesheet}</textarea>
			</div>
			
		</div><!-- tabs -->
		
		<input type="submit" name="submit" value="Save" class="btn sml" onclick="ajaxSaveStylesheet(); return false;"/>
		<input type="submit" name="submit" value="Save and Close" class="btn med" />
		
	</form>
	
</div>
