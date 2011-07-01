function ajaxSavePage() {
	
	var submitData = $('#pageForm').serialize();
	
	$.ajax({
		url: httpUrl+'admin/content/editpage?ajaxsave=1',
		dataType: 'json',
		type: 'POST',
		data: submitData,
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Error sending save request: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			// update identifer and url as they may have been generated
			$('input[name="page_id"]').val(data.id);
			$('input[name="page_title"]').val(data.title);
			$('input[name="page_keyname"]').val(data.keyName);
			$('input[name="page_url"]').val(data.url);
			displayMessage('success','Saved.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}
