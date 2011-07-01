$(document).ready(function(){
	
	// Datepicker
	$("#datepicker").datepicker();
	$(".datepicker").datepicker();
	
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
	
	// Tabby
	$(".code").tabby();
	
	// Alternate Table Rows
	$('.zebra tr:even, .zebra li:even, .hug .item:odd').addClass('odd');
	
	// Tabs
	$('#tabs').tabs();
	$('#tabs2').tabs();
	$('#tabs3').tabs();
	$('#tabs4').tabs();
	$('#tabs5').tabs();
	$('#tabs6').tabs();
	
	// Print
	$('.print').click(function() { window.print(); return false; });
	
	// Temporary Message
	$('.temp').slideDown(1200).delay(3500).slideUp('slow');
	
	// Sort
	$(".sortable").sortable({
		opacity: 0.7,
		helper: 'clone',
		cursor: 'move',
		axis: 'y',
		tolerance: 'pointer',
		update: function(event, ui) {
			var order = $(this).sortable("toArray").join();
			
			var saveUrl = $(this).attr('saveurl');
			if(saveUrl) {
				$.ajax({
					url: saveUrl,
					data: "order="+order
				});
			}
		}
    });
	$(".sortable").selectable();
	
	$('.sortable').bind('mousedown', function(e) {
	  e.stopPropagation();
	});
	
	// Tool
	
	$("#code-toggle").click(function(){
		$(".code").ckeditor();
	});
	
	//WYSIWYG - CKEditor
	$('textarea.wysiwyg').ckeditor();


});
