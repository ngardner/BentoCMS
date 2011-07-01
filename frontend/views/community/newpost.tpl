{include file=tpl/community/header.tpl}

<div class="pad20">

<h2>{$category.name}</h2>

<form action="{$httpUrl}community/newpost.html" method="post">
	<input type="hidden" name="catid" value="{$category.id}">
	<input type="hidden" name="newPost" value="1">
	
	<table width="100%" cellspacing="0">
		<tr>
			<th class="first last" colspan="2">Adding a New Thread</th>
		</tr>
		<tr>
			<td width="200"><b>Topic</b></td>
			<td><input type="text" class="text small" name="topic"></td>
		</tr>
		<tr>
			<td class="top"><b>Message</b></td>
			<td><textarea rows="5" cols="50" name="message" class="textarea"></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="image" src="{$skin}img/btn-submit-post.png"/></td>
		</tr>
	</table>

</form>

</div><!-- pad -->

</div>
</div>