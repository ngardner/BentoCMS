{include file=tpl/community/header.tpl}

<div class="pad20">

	{if $errorMsg}
		<div class="error">{$errorMsg}</div>
	{/if}
	
	{if $thread.topic.isactive}
		
		<div class="action">
		{if $isadmin}
			<a id="stickyToggle" href="javascript:stickyToggle('{$thread.topic.id}')" class="butt">{if $thread.topic.sticky == 1}Unsticky{else}Sticky{/if}</a>
			<a id="lockToggle" href="javascript:lockToggle('{$thread.topic.id}')" class="butt">{if $thread.topic.locked == 1}Unlock{else}Lock{/if}</a>
			<a id="deleteThread" href="javascript:deleteThread('{$thread.topic.id}')" class="butt">Delete Thread</a>
		{else}
			<a href="#reply" class="butt">+ Post a Reply</a>
		{/if}
		</div>
		
		<span>
			{if $thread}
			<a href="{$httpUrl}community/{'/[^a-zA-Z]/'|preg_replace:'_':$channel.name}-{$channel.id}/{'/[^a-zA-Z]/'|preg_replace:'_':$category.name}-{$category.id}-1.html">&laquo; back to {$category.name}</a>
			{/if}
		</span><br/><br/>
		
		<table width="100%" class="zebra" cellspacing="0">
			<tr>
				<th class="first last" colspan="2">{$thread.topic.topic}</th>
			</tr>
			<!--Main topic posted by original author -->
			<tr>
				<td class="author" width="150">{$thread.topic.author}{if $thread.topic.admin == 1}<br><i class="small">Staff</i>{/if}</td>
				<td>
					<b>{$thread.topic.topic}</b><br/><br/>
					{$thread.topic.message|nl2br}<br/><br/>
					<p class="posted">Posted on {$thread.topic.cdate|date_format:"<b>%m/%d/%Y</b> at <b>%r</b>"}</p></td>
			</tr>
			<!-- Replies to main topic -->
			
			{foreach from=$thread.replies item=post}
			<tr>
				<td class="author">
					{$post.author}
				</td>
				<td class="replies">
					<a name="{$post.id}"></a>
					{$post.message|nl2br}<br/><br/>
					{if $isadmin}<a id="deletePost" href="javascript:deletePost('{$post.id}')" style="float: right;">Delete Post</a>{/if}
					<p class="posted">Posted on {$post.cdate|date_format:"<b>%m/%d/%Y at</b> <b>%r</b>"}</p>
				</td>
			</tr>
			{/foreach}
			
		</table>
		
		{assign var="pageType" value='viewThread'}
		{include file=tpl/community/pagination.tpl}
	
		<br><br>
		{if $thread.topic.locked}
			<table width="100%">
				<tr>
					<th class="first last">Post a reply</th>
				</tr>
				<tr>
					<td><img src="{$skin}img/icon/icon-lock.png" alt="locked"/> This thread is locked.</td>
				</tr>
			</table>
		{else}
			{if $loggedIn}
			<form action="{$httpUrl}community/newreply.html" method="post">
				<input type="hidden" name="threadId" value="{$thread.topic.id}">
				<input type="hidden" name="newPost" value="1">
				
				<table width="100%" cellspacing="0">
					<tr>
						<th colspan="2" class="first last">Post a reply</th>
					</tr>
					<tr>
						<td width="200"><b>Topic</b></td>
						<td><input type="text" class="text small" name="topic"></td>
					</tr>
					<tr>
						<td class="top"><b>Message</b></td>
						<td><textarea rows="5" name="message" class="textarea"></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="image" src="{$skin}img/btn-post-reply.png"/></td>
					</tr>
				</table>
			</form>
			{else}
				<a name="reply"> </a>
				<table width="100%">
					<tr>
						<th class="first last">Post a reply</th>
					</tr>
					<tr>
						<td>You must be <a href="{$httpUrl}community/login.html">logged in</a> to reply.</td>
					</tr>
				</table>
			{/if}
		{/if}
		<br><br>

	{else}
		This is not an active thread
	{/if}

</div><!-- pad -->

</div>
</div>