<script type="text/javascript" src="{$skin}js/page.js"></script>

<h1>{if $pageInfo}Edit{else}Creating{/if} Page</h1>

<div class="pad25">

	{include file="tpl/content/pages_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/content/editpage" id="pageForm">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="page_id" value="{$pageInfo.id}"/>
		<input type="hidden" name="page_displayOrder" value="{$pageInfo.displayOrder}"/>
		
		<div id="tabs">
			<div class="bar">
				<ul>
					<li><a href="#pageParent" onclick="return false;">Parent</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="pageParent">
				<select name="page_parent_id" class="select">
					<option value="none">none</option>{include file="tpl/content/pages_list_select.tpl"}
				</select>
			</div>
			
		</div><!-- tab-->
		
		<div id="tabs2">
		
			<div class="bar">
				<ul>
					<li><a href="#pageTitle" onclick="return false;">Page Title</a></li>
					<li><a href="#pageIdentifier" onclick="return false;">Identifier</a></li>
					<li><a href="#pageUrl" onclick="return false;">URL</a></li>
					<li><a href="#pageTarget" onclick="return false;">Target</a></li>
					<li><a href="#meta" onclick="return false;">Meta</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="pageTitle">
				<input type="text" name="page_title" value="{$pageInfo.title}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="pageIdentifier">
				<input type="text" name="page_keyname" class="text" value="{$pageInfo.keyName}"/><br/>
				<span class="small">used for blocks</span> <span class="small">(optional, will autogenerate)</span>
			</div>
			
			<div class="block_content tab_content" id="pageUrl">
				<input type="text" name="page_url" class="text" value="{$pageInfo.url}"/><br/>
				<span class="small">(optional, will autogenerate)</span>
			</div>
			
			<div class="block_content tab_content" id="pageTarget">
				<select name="page_windowaction" class="select">
					<option value="_self" {if $pageInfo.windowaction=='_self'}selected="selected"{/if}>Same window</option>
					<option value="_blank" {if $pageInfo.windowaction=='_blank'}selected="selected"{/if}>New window</option>
				</select>
			</div>
			
			<div class="block_content tab_content" id="meta">
				<table width="100%">
					<tr>
						<td width="15%"><label>Title</label></td>
						<td><input type="text" name="meta_title" value="{$pageInfo.meta.title}" class="text lrg"/></td>
					</tr>
					<tr>
						<td><label>Description</label></td>
						<td><input type="text" name="meta_description" value="{$pageInfo.meta.description}" class="text lrg"/></td>
					</tr>
					<tr>
						<td><label>Keywords</label></td>
						<td><input type="text" name="meta_keywords" value="{$pageInfo.meta.keywords}" class="text lrg"/></td>
					</tr>
				</table>
			</div>
		
		</div><!-- tabs -->
		
		<div id="tabs3">
			
			<div class="bar">
				<ul>
					<li><a href="#mainContent" onclick="return false;">Content</a></li>
					<li><a href="#sidebarLeft" onclick="return false;">Left Sidebar</a></li>
					<li><a href="#sidebarRight" onclick="return false;">Right Sidebar</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="mainContent">
				<textarea name="page_content" class="textarea code wysiwyg">{$pageInfo.content}</textarea>
			</div>
			
			<div class="block_content tab_content" id="sidebarLeft">
				<textarea name="page_sidebars[left]" class="textarea code">{$pageInfo.sidebars.left.content}</textarea>
			</div>
			
			<div class="block_content tab_content" id="sidebarRight">
				<textarea name="page_sidebars[right]" class="textarea code">{$pageInfo.sidebars.right.content}</textarea>
			</div>
			
		</div><!-- tabs -->
		
		<div class="section">
		
		<label>Layout</label>
		<select name="page_layout_id" class="select sml">
			{foreach from=$layouts item=layout}
				<option value="{$layout.id}" {if $layout.id == $pageInfo.layout_id}selected="selected"{/if}>{$layout.title}</option>
			{/foreach}
		</select>
		
		<label>Status</label>
		<select name="page_status" class="select sml">
			<option value="published" {if $pageInfo.status=='published'}selected="selected"{/if}>published</option>
			<option value="draft" {if $pageInfo.status=='draft'}selected="selected"{/if}>draft</option>
			<option value="hidden" {if $pageInfo.status == 'hidden'}selected = "selected"{/if}>hidden</option>
		</select>
		
		</div><!-- section -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml" onclick="ajaxSavePage(); return false;"/>
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
			{if $pageInfo.url}<a href="{$pageInfo.url}?preview=1" class="butt" target="_blank">View Page</a>{/if}
		</div>
		
	</form>
	
</div>
