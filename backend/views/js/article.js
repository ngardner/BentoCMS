function ajaxSaveArticle() {
	
	var submitData = $('#articleForm').serialize();
	
	$.ajax({
		url: httpUrl+'admin/blog/editarticle?ajaxsave=1',
		dataType: 'json',
		type: 'POST',
		data: submitData,
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Error sending save request: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			// update identifer and id as they may have been generated
			$('input[name="article_id"]').val(data.id);
			$('input[name="article_title"]').val(data.title);
			$('input[name="article_keyName"]').val(data.keyName);
			$('input[name="article_url"]').val(data.url);
			$('input[name="article_publishDate"]').val(data.publishDate);
			displayMessage('success','Saved.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}
