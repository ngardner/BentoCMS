<h1>Dashboard</h1>

<div class="pad25">
	
	<div class="half left">
		<div class="pad10">
			<h5>Latest User Logins <a href="/admin/users/index" class="more">all users</a></h5>
			<table width="100%" class="border table zebra">
				<tr>
					<th>User (company)</th>
					<th width="35%">Date</th>
				</tr>
				{foreach from=$latestLogins item=userLogin}
				<tr>
					<td>
						<a href="/admin/users/edituser?user_id={$userLogin.id}">{$userLogin.fName} {$userLogin.lName}</a>
						{if $userLogin.company}<span class="small grey">({$userLogin.company})</span>{/if}
					</td>
					<td class="small">{$userLogin.lastLogin|timeSpan}</td>
				</tr>
				{/foreach}
			</table>
		</div>
	</div>
	
	<div class="half right">
		<div class="pad10">
			<h5>Popular Searches <a href="" class="more">all</a></h5>
			<table width="100%" class="border table zebra">
				<tr>
					<th>Keyword</th>
					<th width="10">Count</th>
				</tr>
				{foreach from=$popularSearches item=search}
					<tr>
						<td class="small">{$search.searchQuery}</td>
						<td class="small center">{$search.count}</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan="2">No searches</td>
					</tr>
				{/foreach}
			</table>
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="pad10">
		<h5>New Comments To Approve <a href="/admin/blog/comments?status=pending" class="more">all</a></h5>
		<table width="100%" class="border table zebra">
			<tr>
				<th>Comment</th>
			</tr>
			{foreach from=$latestComments item=comment}
				<tr>
					<td>
						<a href="/admin/blog/comments?status=pending">{$comment.comment|truncate:40}</a>
					</td>
				</tr>
			{/foreach}
		</table>
	</div>

	<div class="clear"></div>

</div>
