function ajaxSaveLayout() {
	
	var submitData = $('#layoutForm').serialize();
	
	$.ajax({
		url: httpUrl+'admin/design/editlayout?ajaxsave=1',
		dataType: 'json',
		type: 'POST',
		data: submitData,
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Error sending save request: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			// update identifer and id as they may have been generated
			$('input[name="layout_id"]').val(data.id);
			$('input[name="layout_title"]').val(data.title);
			displayMessage('success','Saved.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}
