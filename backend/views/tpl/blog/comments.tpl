<h1>Comments</h1>

<script type="text/javascript" src="{$skin}js/comment.js"></script>

<div class="pad25">

	<div class="action">
		<a href="{$httpUrl}admin/blog/comments" class="butt">All Comments</a>
		<a href="{$httpUrl}admin/blog/comments?status=pending" class="butt">Pending Comments</a>
		<a href="{$httpUrl}admin/blog/comments?status=approved" class="butt">Approved Comments</a>
		<a href="{$httpUrl}admin/blog/comments?status=spam" class="butt">Spam Comments</a>
	<div class="clear"></div>
	</div>
	
	<ul class="item-title">
		<li class="col-last">Status</li>
		<li class="col">Article</li>
		<li class="col">Author</li>
		<li class="col-first">Comment</li>
	</ul>
	
	<div class="hug">
	{if !empty($commentList)}
			{foreach from=$commentList item=comment}
			<div class="item">
			
				<a href="{$httpUrl}admin/blog/deletecomment?comment_id={$comment.id}" onclick="return confirm('Are you sure you want to delete this comment?');" class="icon-trash"><img src="{$skin}img/icon-trash.gif" alt="delete"/></a>
				
				<div class="left">
					{$comment.comment|truncate:60}
				</div>
				
				
				<div class="col">
					<select id="status{$comment.id}" onchange="javascript:updateStatus({$comment.id})">
						<option value="pending" {if $comment.status == 'pending'}selected="selected"{/if}>Pending</option>
						<option value="approved" {if $comment.status == 'approved'}selected="selected"{/if}>Approved</option>
						<option value="spam" {if $comment.status == 'spam'}selected="selected"{/if}>Spam</option>
					</select>
				</div>
				<div class="col"><a href="{$httpUrl}admin/blog/editarticle?article_id={$comment.article_id}">{$comment.title|truncate:20}</a></div>
				<div class="col">{if $comment.user_id != 0}<a href="{$httpUrl}admin/users/edituser?user_id={$comment.user_id}">{$comment.name}</a>{else}{$comment.name}{/if}</div>
				
				
			<div class="clear"></div>
			</div>
			{/foreach}
	{else}
		- no comments yet -
	{/if}
	</div>
	
</div>
