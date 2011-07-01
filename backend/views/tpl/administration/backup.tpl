<h1>Backup Database</h1>

<div class="pad25">
	
	<ul>
		<li><b>Quick Note</b></li>
		<li class="small">1. This process makes a copy of the <b>database only</b>, and stores it in a compressed zip file.</li>
		<li class="small">2. Does not backup files / images / downloads / server settings.</li>
		<li class="small">3. Download the backup to your computer for safe keeping.</li>
	</ul>
	
	<br/>
	
	{if $currentBackups}
	<table width="100%" class="table zebra">
		<tr>
			<th>File</th>
			<th width="15%">Size</th>
			<th width="15%">Date</th>
			<th width="15%">&nbsp;</th>
		</tr>
		{foreach from=$currentBackups item=backup}
		<tr>
			<td>{$backup.file}</td>
			<td class="small grey">{$backup.size}</td>
			<td class="small grey">{$backup.timestamp|timeSpan}</td>
			<td><a href="/admin/backup/download?file={$backup.file}" class="right">Download</a></td>
		</tr>
		{/foreach}
		</table>
	{else}
		
		<p>No backups current exist!</p>
		
	{/if}
	
	<br/>
	
	<form method="post" action="{$httpUrl}admin/backup/create">
		<input type="hidden" name="dosubmit" value="1"/>
		<input type="submit" value="Backup Database" class="btn"/>
	</form>

</div>
