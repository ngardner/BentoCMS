<div class="action">
	<a href="{$httpUrl}admin/form/forms" class="butt">Current Forms</a>
	{if !empty($submissionInfo)}
	<a href="{$httpUrl}admin/form/viewsubmissions?form_id={$submissionInfo.0.form_id}" class="butt">Back to Submissions</a>
	{/if}
	<a href="{$httpUrl}admin/form/createform" class="butt">+ Add Form</a>
<div class="clear"></div>
</div>

