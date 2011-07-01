<script type="text/javascript" src="{$skin}js/article.js"></script>

<h1>{if $articleInfo.id}Edit{else}Creating{/if} Article</h1>

<div class="pad25">

	{include file="tpl/blog/articles_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/blog/editarticle" id="articleForm">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="article_id" value="{$articleInfo.id}"/>
		
		<div id="tabs">
			
			<div class="bar">
				<ul>
					<li><a href="#articleCategory" onclick="return false;">Category</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="articleCategory">
				<select name="article_category_id" class="select">
					<option value="none">none</option>{include file="tpl/blog/categories_list_select.tpl"}
				</select>
			</div>
			
		</div><!-- tabs -->
		
		<div id="tabs2">
		
			<div class="bar">
				<ul>
					<li><a href="#articleTitle" onclick="return false;">Article Title</a></li>
					<li><a href="#articleIdentifier" onclick="return false;">Identifier</a></li>
					<li><a href="#articleUrl" onclick="return false;">URL</a></li>
					<li><a href="#articleDate" onclick="return false;">Date</a></li>
					<li><a href="#metaTitle" onclick="return false;">Meta Title</a></li>
					<li><a href="#metaDescription" onclick="return false;">Meta Description</a></li>
					<li><a href="#metaKeywords" onclick="return false;">Meta Keywords</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="articleTitle">
				<input type="text" name="article_title" value="{$articleInfo.title}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="articleIdentifier">
				<input type="text" name="article_keyName" class="text" value="{$articleInfo.keyName}"/><br/>
				<span class="small">used for blocks</span> <span class="small">(optional, will autogenerate)</span>
			</div>
			
			<div class="block_content tab_content" id="articleUrl">
				<input type="text" name="article_url" class="text" value="{$articleInfo.url}"/><br/>
				<span class="small">(optional, will autogenerate)</span>
			</div>
			
			<div class="block_content tab_content" id="articleDate">
				<input type="text" name="article_publishDate" class="text" value="{$articleInfo.publishDate}" id="datepicker"/><br/>
				<span class="small">(optional, will autogenerate to today)</span>
			</div>
			
			<div class="block_content tab_content" id="metaTitle">
				<input type="text" name="meta_title" value="{$articleInfo.meta.title}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="metaDescription">
				<input type="text" name="meta_description" value="{$articleInfo.meta.description}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="metaKeywords">
				<input type="text" name="meta_keywords" value="{$articleInfo.meta.keywords}" class="text"/>
			</div>
		
		</div><!-- tabs -->
		
		<div id="tabs3">
			
			<div class="bar">
				<ul>
					<li><a href="#mainContent" onclick="return false;">Article Content</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="mainContent">
				<textarea name="article_article" class="textarea code wysiwyg">{$articleInfo.article}</textarea>
			</div>
			
		</div><!-- tabs -->
		
		<div class="section">
		
		<label>Author</label>
		<select name="article_author_id" class="select sml">
			{foreach from=$userList item=author}
				<option value="{$author.id}" {if $author.id == $articleInfo.author_id}selected="selected"{/if}>{$author.lname}, {$author.fname}</option>
			{/foreach}
		</select>
		
		<label>Layout</label>
		<select name="article_layout_id" class="select sml">
			{foreach from=$layouts item=layout}
				<option value="{$layout.id}" {if $layout.id == $articleInfo.layout_id}selected="selected"{/if}>{$layout.title}</option>
			{/foreach}
		</select>
		
		<label>Status</label>
		<select name="article_status" class="select sml">
			<option value="published" {if $articleInfo.status=='published'}selected="selected"{/if}>published</option>
			<option value="draft" {if $articleInfo.status=='draft'}selected="selected"{/if}>draft</option>
			<option value="hidden" {if $articleInfo.status == 'hidden'}selected="selected"{/if}>hidden</option>
		</select>
		
		</div><!-- section -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml" onclick="ajaxSaveArticle(); return false;" />
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
			{if $articleInfo.url}<a href="{$articleInfo.url}?preview=1" class="butt" target="_blank">View Article</a>{/if}
		</div>
		
	</form>
	
</div>
