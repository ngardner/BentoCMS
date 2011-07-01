function ajaxSaveBlock() {
	
	var submitData = $('#blockForm').serialize();
	
	$.ajax({
		url: httpUrl+'admin/content/editblock?ajaxsave=1',
		dataType: 'json',
		type: 'POST',
		data: submitData,
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Error sending save request: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			// update identifer and id as they may have been generated
			$('input[name="block_id"]').val(data.id);
			$('input[name="block_title"]').val(data.title);
			$('input[name="block_keyname"]').val(data.keyName);
			displayMessage('success','Saved.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}
