function ajaxSaveCategory() {
	
	var submitData = $('#categoryForm').serialize();
	
	$.ajax({
		url: httpUrl+'admin/blog/editcategory?ajaxsave=1',
		dataType: 'json',
		type: 'POST',
		data: submitData,
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Error sending save request: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			// update identifer and id as they may have been generated
			$('input[name="category_id"]').val(data.id);
			$('input[name="category_title"]').val(data.title);
			$('input[name="category_keyName"]').val(data.keyName);
			$('input[name="category_url"]').val(data.url);
			displayMessage('success','Saved.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}
