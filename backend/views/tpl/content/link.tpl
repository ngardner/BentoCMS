<h1>{if $pageInfo}Edit{else}Creating{/if} Link</h1>

<div class="pad25">

	{include file="tpl/content/pages_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/content/editpage">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="page_id" value="{$pageInfo.id}"/>
		<input type="hidden" name="page_displayOrder" value="{$pageInfo.displayOrder}"/>
		<input type="hidden" name="page_type" value="link"/>
		<input type="hidden" name="type" value="link"/>
		<input type="hidden" name="page_status" value="published"/>
		
		<div id="tabs">
			<div class="bar">
				<ul>
					<li><a href="#linkParent" onclick="return false;">Parent</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="linkParent">
				<select name="page_parent_id" class="select">
					<option value="none">none</option>{include file="tpl/content/pages_list_select.tpl"}
				</select>
			</div>
			
		</div><!-- tab-->
		
		<div id="tabs2">
			<div class="bar">
				<ul>
					<li><a href="#linkTitle" onclick="return false;">Link Title</a></li>
					<li><a href="#linkIdentifier" onclick="return false;">Identifier</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="linkTitle">
				<input type="text" name="page_title" value="{$pageInfo.title}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="linkIdentifier">
				<input type="text" name="page_keyName" value="{$pageInfo.keyName}" class="text"/>
			</div>

		</div><!-- tab -->
		
		<div id="tabs3">
			<div class="bar">
				<ul>
					<li><a href="#linkURL" onclick="return false;">URL</a></li>
					<li><a href="#linkTarget" onclick="return false;">Target</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="linkURL">
				<input type="text" name="page_content" value="{$pageInfo.content}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="linkTarget">
				<select name="page_windowaction" class="select">
					<option value="_self" {if $pageInfo.windowaction=='_self'}selected="selected"{/if}>Same window</option>
					<option value="_blank" {if $pageInfo.windowaction=='_blank'}selected="selected"{/if}>New window</option>
				</select>
			</div>
			
		</div><!-- tab -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml" />
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
		</div>
		
	</form>
	
</div>
