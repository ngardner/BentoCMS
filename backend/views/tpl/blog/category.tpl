<script type="text/javascript" src="{$skin}js/category.js"></script>

<h1>{if $categoryInfo.id}Edit{else}Creating{/if} Category</h1>

<div class="pad25">

	{include file="tpl/blog/categories_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/blog/editcategory" id="categoryForm">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="category_id" value="{$categoryInfo.id}"/>
		<input type="hidden" name="category_displayOrder" value="{$categoryInfo.displayOrder}"/>
		
		<div id="tabs">
			<div class="bar">
				<ul>
					<li><a href="#categoryParent" onclick="return false;">Parent</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="categoryParent">
				<select name="category_parent_id" class="select">
					<option value="none">none</option>{include file="tpl/blog/categories_list_select.tpl"}
				</select>
			</div>
			
		</div><!-- tab-->
		
		<div id="tabs2">
		
			<div class="bar">
				<ul>
					<li><a href="#categoryTitle" onclick="return false;">Category Title</a></li>
					<li><a href="#categoryIdentifier" onclick="return false;">Identifier</a></li>
					<li><a href="#categoryUrl" onclick="return false;">URL</a></li>
					<li><a href="#sidebarLeft" onclick="return false;">Left Sidebar</a></li>
					<li><a href="#sidebarRight" onclick="return false;">Right Sidebar</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="categoryTitle">
				<input type="text" name="category_title" value="{$categoryInfo.title}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="categoryIdentifier">
				<input type="text" name="category_keyName" class="text" value="{$categoryInfo.keyName}"/><br/>
				<span class="small">used for blocks</span> <span class="small">(optional, will autogenerate)</span>
			</div>
			
			<div class="block_content tab_content" id="categoryUrl">
				<input type="text" name="category_url" class="text" value="{$categoryInfo.url}"/><br/>
				<span class="small">(optional, will autogenerate)</span>
			</div>
			
			<div class="block_content tab_content" id="sidebarLeft">
				<textarea name="category_leftsidebar" class="textarea code">{$categoryInfo.leftsidebar}</textarea>
			</div>
			
			<div class="block_content tab_content" id="sidebarRight">
				<textarea name="category_rightsidebar" class="textarea code">{$categoryInfo.rightsidebar}</textarea>
			</div>
			
		</div><!-- tabs -->
		
		<div class="section">
		
		<label>Layout</label>
		<select name="category_layout_id" class="select sml">
			{foreach from=$layouts item=layout}
				<option value="{$layout.id}" {if $layout.id == $categoryInfo.layout_id}selected="selected"{/if}>{$layout.title}</option>
			{/foreach}
		</select>
		
		</div><!-- section -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml" onclick="ajaxSaveCategory(); return false;" />
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
		</div>
		
	</form>
	
</div>
