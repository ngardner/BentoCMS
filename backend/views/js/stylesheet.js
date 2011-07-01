function ajaxSaveStylesheet() {
	
	if(validStylesheetForm()) {
		
		var submitData = $('#stylesheetForm').serialize();
		
		$.ajax({
			url: httpUrl+'admin/design/editStylesheet?ajaxsave=1',
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
	
}

function validStylesheetForm() {
	
	//required fields
	var filename = $('input[name="css_filename"]').val();
	
	if(filename) {
		
		return true;
		
	} else {
		
		displayMessage('error','Filename is required.');
		return false;
		
	}
	
}
