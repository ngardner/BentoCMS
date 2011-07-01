<h1>{if $showInfo}Edit{else}Creating{/if} Slideshow</h1>

<div class="pad25">

	{include file="tpl/slideshows/slideshow_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/slideshows/editshow" enctype="multipart/form-data">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="show_id" value="{$showInfo.id}"/>
		
		<div id="tabs">
			
			<div class="bar">
				<ul>
					<li><a href="#showTitle" onclick="return false;">Block Title</a></li>
					<li><a href="#showIdentifier" onclick="return false;">Identifier</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="showTitle">
				<input type="text" name="show_title" value="{$showInfo.title}" class="text"/>
			</div>
			
			<div class="block_content tab_content" id="showIdentifier">
				<input type="text" name="show_keyname" value="{$showInfo.keyName}" class="text"/><br/>
				<span class="small">used for blocks</span> <span class="small">(optional, will autogenerate)</span>
			</div>
			
		</div><!-- tabs -->
		
		<div id="tabs2">
			
			<div class="bar">
				<ul>
					<li><a href="#showSettings" onclick="return false;">Settings</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="showSettings">
				<label>Transition</label> <select name="show_transition"><option value="fade" {if $showInfo.transition=='fade'}selected="selected"{/if}>Fade</option></select> &nbsp; &nbsp; &nbsp;
				<label>Delay</label> <input type="text" name="show_delay" value="{$showInfo.delay}" class="text tny"/> <span class="small">sec(s)</span> &nbsp; &nbsp; &nbsp;
				<label>Width</label>  <input type="text" name="show_width" value="{$showInfo.width}" class="text tny"/> <span class="small">px</span> &nbsp; &nbsp; &nbsp;
				<label>Height</label>  <input type="text" name="show_height" value="{$showInfo.height}" class="text tny"/> <span class="small">px</span>
			</div>
			
			
		</div><!-- tabs -->
		
		<div id="tabs3">
			
			<div class="bar">
				<ul>
					<li><a href="#showSlides" onclick="return false;">Slides</a></li>
				</ul>
			</div>
			<div class="sortable block_content tab_content" id="showSlides" saveurl="{$httpUrl}admin/slideshows/saveslideorder">
				
				{foreach from=$slideList item=slide}
					
					<div class="white sort" id="slide_{$slide.id}">
						<img src="/image.php?f={$slide.image}&amp;h=95-&amp;w=95&amp;effect=crop" align="left" style="float: left; margin-right: 15px;"/>
							<input type="hidden" name="show_slides[{$slide.id}][id]" value="{$slide.id}"/>
							<input type="hidden" name="show_slides[{$slide.id}][image]" value="{$slide.image}"/>
							<div class="pad5">
							<label>Title</label> <input type="text" name="show_slides[{$slide.id}][title]" value="{$slide.title}" class="text sml"/>
							<label>Link</label> <input type="text" name="show_slides[{$slide.id}][link]" value="{$slide.link}" class="text sml"/>
							<input type="checkbox" name="show_slides[{$slide.id}][windowaction]" value="1" {if $slide.windowaction=='_blank'}checked="checked"{/if}> Open in new window
							<div class="pad5"/></div>
							<label>Desc</label> <input type="text" name="show_slides[{$slide.id}][description]" value="{$slide.description}" class="text lrg"/>
							<a href="{$httpUrl}admin/slideshows/deleteslide?slide_id={$slide.id}&show_id={$showInfo.id}" onclick="return confirm('Are you sure you want to delete this slide?');" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
							</div>
							
					<div class="clear"></div>
					</div>
					
				{foreachelse}
					
					<span class="small">No slides uploaded. Upload below</span>
					
				{/foreach}

			</div>
			
			
		</div><!-- tabs -->
		
		<div id="tabs4">
			
			<div class="bar">
				<ul>
					<li><a href="#upload" onclick="return false;">Upload</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="upload">
			
				<ul class="uploads">
					<li><input type="file" name="newslide[]"/></li>
					<li><input type="file" name="newslide[]"/></li>
					<li><input type="file" name="newslide[]"/></li>
					<li><input type="file" name="newslide[]"/></li>
				</ul>
				
			<div class="clear"></div>
			</div>
			
			
		</div><!-- tabs -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml"/>
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
		</div>
		
	</form>
		
</div>
