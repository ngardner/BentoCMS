<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>{$ProductTitle} Admin</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

<style type="text/css" media="all">
		@import url("{$bento}css/reset.css");
		@import url("{$bento}css/ui.css");
		@import url("{$skin}css/base.css");
		@import url("{$skin}css/black.css");
</style>

<script src="{$bento}js/jquery.js" type="text/javascript"></script>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script src="{$bento}js/jquery.ui.js" type="text/javascript"></script>
<script src="{$bento}js/jquery.tabby.js" type="text/javascript"></script>
<script src="{$skin}js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="{$skin}js/ckeditor/adapters/jquery.js" type="text/javascript"></script>
<script src="{$skin}js/common.js" type="text/javascript"></script>
<script src="{$skin}js/initiate.js" type="text/javascript"></script>
<script src="{$bento}js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{$bento}js/swfobject.js" type="text/javascript"></script>
<script type="text/javascript">
	var httpUrl = "{$httpUrl}";
</script>

</head>
<body>

<div id="header">
	<div class="wrap">

		<div id="logo"><a href="{$httpUrl}admin/home/index">{$ProductTitle} Admin</a></div>
		<div id="session">
			<a href="/">Dashboard</a> | 
			<a href="{$httpUrl}" target="_blank">View Website</a> | 
			<a href="{$httpUrl}admin/login/logout">Logout</a>
		</div>

	</div><!-- wrap -->
</div><!-- header -->

<div id="menu">
		<div class="wrap">
				
				<ul>
					<li>Content <img src="{$skin}img/icon-drop.gif" alt="drop"/>
						<ul style="display: none;">
							{userHasPermission controller='content' action='pages'}<li><a href="{$httpUrl}admin/content/pages">Pages</a></li>{/userHasPermission}
							{userHasPermission controller='content' action='blocks'}<li><a href="{$httpUrl}admin/content/blocks">Blocks</a></li>{/userHasPermission}
							{userHasPermission controller='slideshows' action='index'}<li><a href="{$httpUrl}admin/slideshows/index">Slideshows</a></li>{/userHasPermission}
							{userHasPermission controller='form' action='index'}<li><a href="{$httpUrl}admin/form/index">Forms</a></li>{/userHasPermission}
							{userHasPermission controller='template' action='templates'}<li><a href="{$httpUrl}admin/template/templates?group=emails">Emails</a></li>{/userHasPermission}
							{userHasPermission controller='template' action='templates'}<li><a href="{$httpUrl}admin/template/templates?group=content">Templates</a></li>{/userHasPermission}
						</ul>
					</li>
					<li>Blog <img src="{$skin}img/icon-drop.gif" alt="drop"/>
						<ul style="display: none;">
							{userHasPermission controller='blog' action='categories'}<li><a href="{$httpUrl}admin/blog/categories">Categories</a></li>{/userHasPermission}
							{userHasPermission controller='blog' action='articles'}<li><a href="{$httpUrl}admin/blog/articles">Articles</a></li>{/userHasPermission}
							{userHasPermission controller='blog' action='comments'}<li><a href="{$httpUrl}admin/blog/comments">Comments</a></li>{/userHasPermission}
							{userHasPermission controller='template' action='templates'}<li><a href="{$httpUrl}admin/template/templates?group=blog">Templates</a></li>{/userHasPermission}
						</ul>
					</li>
					<li>Design <img src="{$skin}img/icon-drop.gif" alt="drop"/>
						<ul style="display: none;">
							{userHasPermission controller='design' action='layouts'}<li><a href="{$httpUrl}admin/design/layouts">Layouts</a></li>{/userHasPermission}
							{userHasPermission controller='design' action='stylesheets'}<li><a href="{$httpUrl}admin/design/stylesheets">Stylesheets</a></li>{/userHasPermission}
						</ul>
					</li>
					<li>Admin <img src="{$skin}img/icon-drop.gif" alt="drop"/>
						<ul style="display: none;">
							{userHasPermission controller='sitesettings' action='index'}<li><a href="{$httpUrl}admin/sitesettings/index">Settings</a></li>{/userHasPermission}
							{userHasPermission controller='users' action='index'}<li><a href="{$httpUrl}admin/users/index">Users</a></li>{/userHasPermission}
							{userHasPermission controller='backup' action='index'}<li><a href="{$httpUrl}admin/backup/index">Backup Database</a></li>{/userHasPermission}
						</ul>
					</li>
				</ul>
				
				<div class="clear"></div>
		</div><!-- wrap -->
</div><!-- menu -->

<div class="wrap">	
	<div id="container">
		<ul id="alerts">
		{foreach from=$messages item=message}
			<li class="{$message.type} temp" style="display: none;">{$message.message}</li>
		{/foreach}
		</ul>
		{$content}
	<div class="clear"></div>	
	</div><!-- container -->
</div><!-- wrap -->

<div id="footer">
	<div class="wrap">
	
			<a href="http://www.bentocms.com">Bento CMS</a>
			
	</div><!-- wrap -->
</div><!-- footer -->
	
</body>
</html>
