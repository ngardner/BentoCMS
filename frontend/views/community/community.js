function selectPage() {
	
	document.location = $('#pageSelector option:selected').attr('url');
	
}

function stickyToggle(threadId) {
		
		$.post(
			httpUrl+"community/sticky.html?threadId="+threadId,
			{

			},
			function(result) {
				
				if(result == 1) {
					$('#stickyToggle').html('Unsticky');
				} else {
					$('#stickyToggle').html('Sticky');
				}
			
			},
			'text'
		);
		
			
}

function lockToggle(threadId) {
		
		$.post(
			httpUrl+"community/lock.html?threadId="+threadId,
			{

			},
			function(result) {
				
				if(result == 1) {
					$('#lockToggle').html('Unlock');
				} else {
					$('#lockToggle').html('Lock');
				}
			
			},
			'text'
		);
		
			
}

function deleteThread(threadId) {
	
	var answer = confirm('Are you sure you want to delete this thread?');
	
	if(answer) {
		
		$.post(
			httpUrl+"community/removeThread.html?threadId="+threadId,
			{

			},
			function(result) {
				
				if(result == true) {
					
					alert('This thread has been removed');
					
					window.location = httpUrl+"community/index.html";
					
				}
			
			},
			'text'
		);
		
	}
	
}

function deletePost(postId) {
	
	var answer = confirm('Are you sure you want to delete this post?');
	
	if(answer) {
		
		$.post(
			httpUrl+"community/removePost.html?postId="+postId,
			{

			},
			function(result) {
				
				if(result == true) {
					
					alert('This post has been removed');
					
					window.location.reload(true);
					
				}
			
			},
			'text'
		);
		
	}
	
}

$(function(){
    
    // Alternate Table Rows
    $('.comm-wrap tr').alternate({},function(){$(this).toggleClass('selected')});
    
});