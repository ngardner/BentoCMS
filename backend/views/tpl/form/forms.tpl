<h1>Forms</h1>

<div class="pad25">

	{include file="tpl/form/form_minimenu.tpl"}
	
	<ul class="item-title">
		<li class="col-first">Title</li>
	</ul>
	
	<div class="hug">
	{if !empty($formList)}
			{foreach from=$formList item=form}
			<div class="item" id="formcontainer_{$form.id}">
				<a href="{$httpUrl}admin/form/deleteform?form_id={$form.id}" onclick="return confirm('Are you sure you want to delete this form?');" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
				
				<div class="half-left">
					<a href="{$httpUrl}admin/form/editform?form_id={$form.id}">{$form.name}</a> <span class="small grey">({$form.keyName})</span>
				</div>
				
			<div class="clear"></div>
			</div>
			{/foreach}
	{else}
		- no forms setup yet -
	{/if}
	</div>
	
	
	{if !empty($formList)}
	<div class="clear"><br/></div>
	<div class="section pad10">
		<form method="post" action="{$httpUrl}admin/form/exportdata">
		<input type="hidden" name="doexport" value="1"/>

			<label>Form</label> 
			<select name="form_id" class="select sml">
				{foreach from=$formList item=form}
				<option value="{$form.id}">{$form.name}</option>
				{/foreach}
			</select>
			
			<label>Start date</label> 
			<input type="text" class="text tny datepicker" name="startDate"/>
			
			<label>End date</label> 
			<input type="text" class="text tny datepicker" name="endDate"/>
			
			<input type="submit" value="Export Data"/>
		
		</form>
	</div>
	{else}
		- no forms setup yet -
	{/if}
	
	
</div>
