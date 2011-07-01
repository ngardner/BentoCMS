function updateStatus(comment_id) {
	
	status = $('#status'+comment_id).val();
	
	$.ajax({
		url: httpUrl+'admin/blog/updateStatus?comment_id='+comment_id+'&status='+status,
		dataType: 'json',
		type: 'POST',
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Error sending save request: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			displayMessage('success','Status Updated.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}
