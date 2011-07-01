<script type="text/javascript" src="{$skin}js/template.js"></script>

<h1>Edit {$group} Template</h1>

<div class="pad25">

	<form method="post" action="{$httpUrl}admin/template/edittemplate" id="templateForm">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="template_id" value="{$templateInfo.id}"/>
		<input type="hidden" name="group" value="{$group}"/>
		
		<div id="tabs">
			
			<div class="bar">
				<ul>
					<li><a href="#templateName" onclick="return false;">Name</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="templateName">
				{$templateInfo.name} ({$templateInfo.keyName})
			</div>
			
		</div><!-- tabs -->
		
		<div id="tabs2">
			
			<div class="bar">
				<ul>
					<li><a href="#mainContent" onclick="return false;">Content</a></li>
					<li><a href="#leftContent" onclick="return false;">Left Sidebar</a></li>
					<li><a href="#rightContent" onclick="return false;">Right Sidebar</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="mainContent">
				<textarea name="template_content" class="textarea code">{$templateInfo.content|htmlspecialchars}</textarea>
			</div>
			
			<div class="block_content tab_content" id="leftContent">
				<textarea name="template_left_sidebar" class="textarea code">{$templateInfo.left_sidebar}</textarea>
			</div>
			
			<div class="block_content tab_content" id="rightContent">
				<textarea name="template_right_sidebar" class="textarea code">{$templateInfo.right_sidebar}</textarea>
			</div>
			
		</div><!-- tabs -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml" onclick="ajaxSaveTemplate(); return false;"/>
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
		</div>
		
	</form>
		
</div>
