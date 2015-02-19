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

function isElementInViewport (el) {

    //special bonus for those using jQuery
    if (el instanceof jQuery) {
        el = el[0];
    }
    
    if (!el) {
	return false;
    }

    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}