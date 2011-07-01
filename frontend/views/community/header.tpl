<script>
{literal}
	$(document).ready(function(){
		$("#search").click(function() {
			if($("#search").val() == "search community...") {
				$("#search").val("");
			}
		});
		
		$("#search").focusout(function() {
			if($("#search").val() == "") {
				$("#search").val("search community...");
			}
		});
	});
{/literal}
</script>

<div class="wrap">

<div class="yoink community">

<script src="{$skin}tpl/community/community.js" type="text/javascript"></script>

<h1>Community</h1>

<div class="bar pad15"> 

	<ul class="menu">
		<li>
			<a href="{$httpUrl}community/index.html">Home</a>  
		</li>
		{if $loggedIn}
		<li>
			<a href="{$httpUrl}community/account.html">Account</a>
		</li>
		{/if}
		<li>
			{if $loggedIn}
			<a href="{$httpUrl}community/logout.html">Logout</a>
			{else}
			<a href="{$httpUrl}community/login.html">Login/Register</a>
			{/if}
		</li>
	</ul>
	
	<form method="post" action="{$httpUrl}community/search.html">
		<input type="hidden" name="submit" value="1">
		<input name="search" id="search" value="search community..." class="text small"/>
		<input type="image" src="{$skin}img/btn/btn-search.png" class="btn-img"/>
	</form>
	
	<div class="clear"></div>
</div><!-- bar -->

<div class="clear"></div>