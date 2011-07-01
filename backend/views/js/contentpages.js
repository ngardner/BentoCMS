$(document).ready(function() {
	
	
	
});

function showhideChildren(parent_id) {
	
	var children = $('#childrenPages_' + parent_id);
	
	if(children.css('display') == 'none') {
		
		$(children).slideDown();
		$('#expandbutton_'+parent_id).html('<img src="/backend/views/img/icon-minus.gif" alt="collapse"/>');
		
	} else {
		
		$(children).slideUp();
		$('#expandbutton_'+parent_id).html('<img src="/backend/views/img/icon-plus.gif" alt="expand"/>');
		
	}
	
}

function expandAllPages() {
	
	$('div[id*="childrenPages_"]').slideDown();
	$('span[id*="expandbutton_"]').html('<img src="/backend/views/img/icon-minus.gif" alt="collapse"/>');
	
}

function collapseAllPages() {
	
	$('div[id*="childrenPages_"]').slideUp();
	$('span[id*="expandbutton_"]').html('<img src="/backend/views/img/icon-plus.gif" alt="expand"/>');
	
}