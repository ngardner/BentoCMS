<script type="text/javascript" src="{$skin}js/form.js"></script>
<script type="text/javascript">
	var initialFormFields = new Array();
</script>

<h1>{if $formInfo}Edit{else}Creating{/if} Form</h1>

<div class="pad25">

	{include file="tpl/form/form_minimenu.tpl"}
	
	<form method="post" action="{$httpUrl}admin/form/editform" id="formForm">
		
		<input type="hidden" name="dosave" value="1"/>
		<input type="hidden" name="form_id" value="{$formInfo.id}"/>
		
		<div id="tabs">
			
			<div class="bar">
				<ul>
					<li><a href="#formInfo" onclick="return false;">Form Info</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="formInfo">
				<table width="100%">
					<tr>
						<td width="20%"><label>Name</label></td>
						<td><input type="text" name="form_name" value="{$formInfo.name}" class="text lrg"/></td>
					</tr>
					<tr>
						<td><label>Identifier</label></td>
						<td><input type="text" name="form_keyname" value="{$formInfo.keyName}" class="text lrg"/></td>
					</tr>
					<tr>
						<td><label>Email To</label></td>
						<td><input type="text" name="form_emailto" value="{$formInfo.emailTo}" class="text lrg"/></td>
					</tr>
					<tr>
						<td><label>Email From</label></td>
						<td><input type="text" name="form_emailfrom" value="{$formInfo.emailFrom}" class="text lrg"/></td>
					</tr>
					<tr>
						<td><label>Email Subject</label></td>
						<td><input type="text" name="form_emailsubject" value="{$formInfo.emailSubject}" class="text lrg"/></td>
					</tr>
					<tr>
						<td><label>Use Captcha</label></td>
						<td>
							<select name="form_captcha" class="select sml"><option value="1" {if $formInfo.captcha}selected="selected"{/if}>Yes</option><option value="0" {if !$formInfo.captcha}selected="selected"{/if}>No</option></select>
						</td>
					</tr>
					<tr>
						<td class="top"><label>Success Message</label></td>
						<td><textarea name="form_thanksmsg" class="textarea lrg">{$formInfo.thanksmsg}</textarea></td>
					</tr>
					<tr>
						<td><label>Include Basics</label></td>
						<td><input type="checkbox" name="form_includebasics" value="1" {if $formInfo}{if $formInfo.includeBasics}checked="checked"{/if}{else}checked="checked"{/if}/></td>
					</tr>
					<tr>
						<td><label>Send to ExactTarget</label></td>
						<td><input type="checkbox" name="form_exacttarget" value="1" {if $formInfo}{if $formInfo.exactTarget}checked="checked"{/if}{else}checked="checked"{/if}/></td>
					</tr>
				</table>

			</div>
			
		</div><!-- tabs -->
		
		<div id="tabs2">
			
			<div class="bar">
				<ul>
					<li><a href="#formFields" onclick="return false;">Form Fields</a></li>
				</ul>
			</div>
			
			<div class="block_content tab_content" id="formFields">
			
			<div class="sortable" saveurl="{$httpUrl}admin/form/savefieldsorder">
				
				{foreach from=$formInfo.fields item=formField}
				
				<div class="item white sortr" id="field_{$formField.id}">
					
				<script type="text/javascript">
				initialFormFields.push('form_fields[{$formField.id}]');
				</script>
				<div id="form_fieldgroup_{$formField.id}">
					<input type="hidden" name="form_fields[{$formField.id}][id]" value="{$formField.id}"/>
					
				<table width="100%" class="form-type">
					
					<tr id="form_fields[{$formField.id}][type]">
						<td width="20%"><label>Type</td>
						<td>
							<select name="form_fields[{$formField.id}][type]" onchange="ShowHideFields('form_fields[{$formField.id}]');" class="select sml">
								<option value="text" {if $formField.type=='text'}selected="selected"{/if}>Text</option>
								<option value="textarea" {if $formField.type=='textarea'}selected="selected"{/if}>Textarea</option>
								<option value="select" {if $formField.type=='select'}selected="selected"{/if}>Select</option>
								<option value="radio" {if $formField.type=='radio'}selected="selected"{/if}>Radio</option>
								<option value="checkbox" {if $formField.type=='checkbox'}selected="selected"{/if}>Checkbox</option>
								<option value="label" {if $formField.type=='label'}selected="selected"{/if}>Label</option>
								<option value="hidden" {if $formField.type=='hidden'}selected="selected"{/if}>Hidden</option>
							</select>
						</td>
					</tr>
					
					<tr id="form_fields[{$formField.id}][name]">
						<td><label>Display Name</label></td> 
						<td><input type="text" name="form_fields[{$formField.id}][name]" value="{$formField.name}" class="text lrg"/></td>
					</tr>
				
					<tr id="form_fields[{$formField.id}][field_name]">
						<td><label>Field Name</label></td> 
						<td><input type="text" name="form_fields[{$formField.id}][field_name]" value="{$formField.field_name}" class="text lrg"/></td>
					</tr>
					
					<tr id="form_fields[{$formField.id}][value]">
						<td><label>Value</label></td> 
						<td><input type="text" name="form_fields[{$formField.id}][value]" value="{$formField.value}" class="text lrg"/> <br/><span class="small">(values to save for check boxes)</span></td>
					</tr>
					
					<tr id="form_fields[{$formField.id}][checked]">
						<td><label>Checked</label></td> 
						<td>
							<select name="form_fields[{$formField.id}][checked]" class="select sml">
								<option value="1" {if $formField.checked}selected="selected"{/if}>Yes</option>
								<option value="0" {if !$formField.checked}selected="selected"{/if}>No</option>
							</select>
						</td>
					</tr>
					
					<tr id="form_fields[{$formField.id}][values]">
						<td><label>Values</label></td> 
						<td><input type="text" name="form_fields[{$formField.id}][values]" value="{$formField.values}" class="text lrg"/> <br/><span class="small">(comma seperated list of values for select and radio types)</span></td>
					</tr>
					
					<tr id="form_fields[{$formField.id}][width]">
						<td><label>Width</label></td> 
						<td><input type="text" name="form_fields[{$formField.id}][width]" value="{$formField.width}" class="text lrg"/></td>
					</tr>
					
					<tr id="form_fields[{$formField.id}][height]">
						<td><label>Height</label></td> 
						<td><input type="text" name="form_fields[{$formField.id}][height]" value="{$formField.height}" class="text lrg"/></td>
					</tr>
					
					<tr id="form_fields[{$formField.id}][required]">
						<td><label>Required</label></td> 
						<td>
							<select name="form_fields[{$formField.id}][required]" class="select sml">
								<option value="1" {if $formField.required}selected="selected"{/if}>Yes</option>
								<option value="0" {if !$formField.required}selected="selected"{/if}>No</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td></td>
						<td style="padding-top: 10px;"><a href="#" onclick="deleteFormField({$formField.id}); return false;"><img src="{$skin}img/icon-trash.gif" alt="-"/> Delete</a></td>
					</tr>
				
				</table>
				</div>
				</div>
				{/foreach}
				

			</div><!-- sort -->

				<div id="formFieldTemplate" style="display: none;">
					<table width="100%" class="form-type add">
						<tr>
							<td><label>Type</label></td> 
							<td>
								<select name="form_new_fields[type][]" class="select sml">
									<option value="text">Text</option>
									<option value="textarea">Textarea</option>
									<option value="select">Select</option>
									<option value="radio">Radio</option>
									<option value="checkbox">Checkbox</option>
									<option value="label">Label</option>
							</select></td>
						</tr>
						<tr>
							<td width="20%"><label>Name</label></td>
							<td><input type="text" name="form_new_fields[name][]" value="" class="text lrg"/></td>
						</tr>
						<tr>
							<td><label>Field Name</label></td> 
							<td><input type="text" name="form_new_fields[field_name][]" value="" class="text lrg"/></td>
						</tr>
						
						<tr>
							<td><label>Value</label></td> 
							<td><input type="text" name="form_new_fields[value][]" value="" class="text lrg"/> <br/><span class="small">(values to save for check boxes)</span></td>
						</tr>
						
						<tr>
							<td><label>Checked</label></td> 
							<td>
								<select name="form_new_fields[checked][]" class="select sml">
									<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><label>Values</label></td> 
							<td><input type="text" name="form_new_fields[values][]" class="text lrg"/> <br/><span class="small">(comma seperated list of values for select and radio types)</small></td>
						</tr>
						<tr>
							<td><label>Width</label></td> 
							<td><input type="text" name="form_new_fields[width][]" class="text lrg"/></td>
						</tr>
						<tr>
							<td><label>Height</label></td> 
							<td><input type="text" name="form_new_fields[height][]" class="text lrg"/></td>
						</tr>
						<tr>
							<td><label>Required</label></td> 
							<td>
								<select name="form_new_fields[required][]" class="select sml">
									<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
								</select>
							</td>
						</tr>
					</table>
				</div>

				<a href="#bottom" onclick="addFormField();" class="butt">+ Add Form Field</a>
				<a name="bottom"></a>
				
				<br/><br/>
				
			</div>
			
		</div><!-- tabs -->
		
		<div class="btns clear">
			<input type="submit" name="submit" value="Save" class="btn sml"/>
			<input type="submit" name="submit" value="Save and Close" class="btn med" />
		</div>
		
	</form>
		
</div>
