function ajaxSaveTemplate() {
	
	var submitData = $('#templateForm').serialize();
	
	$.ajax({
		url: httpUrl+'admin/template/edittemplate?ajaxsave=1',
		dataType: 'json',
		type: 'POST',
		data: submitData,
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Error sending save request: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			displayMessage('success','Saved.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}
