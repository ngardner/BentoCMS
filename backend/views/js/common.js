function displayMessage(type,message) {
	
	var errorHTML = '<li class="temp ' + type + '" style="display: none;">' + message + '</li>';
	$('#alerts').prepend(errorHTML);
	$('html, body').animate({scrollTop:$('#alerts').scrollTop()}, 'slow');
	$('.temp').slideDown('slow').delay(3500).slideUp('slow');
	
	var t=setTimeout("$('#alerts li').remove()",5000);
	
}

function removeMessages() {
	
	$('#alerts li').remove();
	$('.form-err').removeClass('form-err');
	
}