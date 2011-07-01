$(document).ready(function() {
	var i;
	for(i in initialFormFields) {
		ShowHideFields(initialFormFields[i]);
	}
});

function addFormField() {
	
	var contents;
	
	contents = $('#formFieldTemplate').html();
	$('#formFieldTemplate').before(contents);
	
}

function deleteFormField(id) {
	
	$.ajax({
		url: httpUrl+'admin/form/deleteformfield?field_id='+id,
		error: function(httpObject,errorReason,errorThrown) {
			displayMessage('error','Cannot delete: '+errorReason+' | '+errorThrown);
		},
		success: function(data,textStatus,httpObject) {
			$('#form_fieldgroup_'+id).remove();
			displayMessage('success','Deleted field.')
		},
		complete: function(httpObject,textStatus) {
			// nothing to do here
		}
	});
	
}

function ShowHideFields(likeSelector) {
	
	var fields = $("tr[id*='" + likeSelector + "']");
	
	var type = $(":input[name*='" + likeSelector + "[type]']").val();
	
	// hide all by default
	$(fields).hide();
	
	// always show type selector and name
	$("tr[id*='" + likeSelector + "[type]']").show();
	$("tr[id*='" + likeSelector + "[name]']").show();
	
	switch(type) {
		
		case 'text':
			$("tr[id*='" + likeSelector + "[required]']").show();
			$("tr[id*='" + likeSelector + "[validation]']").show();
			$("tr[id*='" + likeSelector + "[field_name]']").show();
			break;
		
		case 'textarea':
			$("tr[id*='" + likeSelector + "[required]']").show();
			$("tr[id*='" + likeSelector + "[width]']").show();
			$("tr[id*='" + likeSelector + "[height]']").show();
			$("tr[id*='" + likeSelector + "[field_name]']").show();
			break;
		
		case 'select':
			$("tr[id*='" + likeSelector + "[required]']").show();
			$("tr[id*='" + likeSelector + "[values]']").show();
			$("tr[id*='" + likeSelector + "[field_name]']").show();
			break;
		
		case 'checkbox':
			$("tr[id*='" + likeSelector + "[field_name]']").show();
			$("tr[id*='" + likeSelector + "[value]']").show();
			$("tr[id*='" + likeSelector + "[checked]']").show();
			$("tr[id*='" + likeSelector + "[required]']").show();
			break;
		
		case 'radio':
			$("tr[id*='" + likeSelector + "[required]']").show();
			$("tr[id*='" + likeSelector + "[field_name]']").show();
			$("tr[id*='" + likeSelector + "[values]']").show();
			break;
		
		case 'hidden':
			$("tr[id*='" + likeSelector + "[field_name]']").show();
			$("tr[id*='" + likeSelector + "[value]']").show();
			break;
		
		case 'label':
			// nothing extra
			break;
		
	}
	
}
