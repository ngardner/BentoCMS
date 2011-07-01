$(document).ready(function(){
	
	// Menu Setup
	$('#menu ul>li>ul').hide();
	
	// Menu
	$('#menu li').each(function() {
		$(this).hover(
			function(){
				$(this).children('#menu ul').stop(true, true).slideDown('fast');
			},
			function(){
				$(this).children('#menu ul').stop(true, true).slideUp('fast');
			}
		);
	});
	
	// Menu
	$('#menu li li').each(function() {
		$(this).hover(
			function(){
				$(this).children('#menu ul li ul').stop(true, true).show('');
			},
			function(){
				$(this).children('#menu ul li ul').stop(true, true).hide('');
			}
		);
	});
	
	// Alternate Table Rows
	$('.zebra tr:even, .zebra li:even, .hug .item:odd').addClass('odd');
	
	// PrettyPhoto
	$("a[rel^='prettyPhoto']").prettyPhoto({theme:'light_squared'});
	
	// Tabs
	$('#tabs').tabs();
	$('#tabs2').tabs();
	$('#tabs3').tabs();
	
	// Print
	$('.print').click(function() { window.print(); return false; });
	
	// Service
	$('.service-wrap .service:odd, .service-wrap2 .service:odd, .service-wrap3 .service:odd').each(function() {
		$(this).addClass('right');
	});
	
	// Slideshow Navi
	$('#slideshow').each(function() {
		$(this).hover(
			function(){
				$('#slideshow .navi').stop(true, true).slideDown('fast');
			},
			function(){
				$('#slideshow .navi').stop(true, true).slideUp('fast');
			}
		);
	});
	
	// Success
	$('.success').fadeIn('slow');
	
	// Temporary Message
	$('.temp').slideDown('slow').delay(3500).slideUp('slow');
	
	$('#siteSearch').autocomplete({
		minLength: 0,
		source: httpUrl+'Search/autocomplete',
		focus: function(event,ui) {
			$("#siteSearch").val(ui.item.title);
			return false;
		},
		select: function(event,ui) {
			window.location.href = httpUrl+ui.item.url;
			return false;
		}
	}).data("autocomplete")._renderItem = function(ul,item) {
		return $("<li></li>")
			.data("item.autocomplete", item )
			.append("<a>" + item.title + "</a>")
			.appendTo(ul);
	};

});
