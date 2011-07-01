<script type="text/javascript" src="{$skin}js/block.js"></script>

<h1>{if $blockInfo}Edit{else}Creating{/if} Block</h1>

<div class="pad25">

	{include file="tpl/content/blocks_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/content/editblock" id="blockForm">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="block_id" value="{$blockInfo.id}"/>
		
		<div id="tabs">
			
			<div class="bar">
				<ul>
					<li><a href="#blockTitle" onclick="return false;">Block Title</a></li>
					<li><a href="#blockIdentifier" onclick="return false;">Identifier</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="blockTitle">
				<input type="text" name="block_title" value="{$blockInfo.title}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="blockIdentifier">
				<input type="text" name="block_keyname" value="{$blockInfo.keyName}" class="text"/><br/>
				<span class="small">used for blocks</span> <span class="small">(optional, will autogenerate)</span>
			</div>
			
		</div><!-- tabs -->
		
		<div class="clear"></div>
		
		<div class="right">
			<a href="#" id="code-toggle" class="tool">Enable Editor</a>
		</div>
		
		<div class="clear"></div>
		
		<div id="tabs2">
			
			<div class="bar">
				<ul>
					<li><a href="#mainContent" onclick="return false;">Content</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="mainContent">
				<textarea name="block_code" class="textarea code">{$blockInfo.code}</textarea>
			</div>
			
		</div><!-- tabs -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml" onclick="ajaxSaveBlock(); return false;"/>
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
		</div>
		
	</form>
		
</div>
