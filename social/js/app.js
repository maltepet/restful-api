
$(function() {
	
	$('.favorite').hover(
		function(){
			$(this).find('i').addClass('yellow').removeClass('empty');
		},
		function() {
			$(this).find('i').removeClass('yellow').addClass('empty');
		}
	);
	
	$('.favorite').click(function() {
		
		var url = 'http://social.mattaltepeter.com/posts/favorite';
		var data = { 'post_id' : $(this).data('post-id') };
		
		$.post(url, data, function(result) {
			var json = JSON.parse(result);
			
			if ( json.success ) {
				$(this).find('i').addClass('yellow').removeClass('empty').addClass('is-favorite');
			}
			console.log(result);3
		});
	});
	
	$('body').on('click', '.follow-btn', function() {
		var btn = $(this);
		
		var user_id = $(this).data('user-id');
		var data = { 'user_id' : user_id };
		
		$.post('http://social.mattaltepeter.com/users/followuser', data, function(result) {
			console.log(result);
			var json = JSON.parse(result);
			
			if ( json.success ) {
				btn.removeClass('teal').addClass('green').removeClass('follow-btn').addClass('following-btn').html('<i class="checkmark icon"></i> Following');
			}
		});
		
	});
	
	$('.ui.dropdown').dropdown();
	
	$('body').on('click', '.delete-link', function(e) {
		e.preventDefault();
		var c = confirm('Are you sure you wish to delete this post?');
		
		if ( c ) {
			var post_id = $(this).data('post-id');
			var url = 'http://rest.mattaltepeter.com/posts/delete/' + post_id;
			
			$.get(url, function(data) {
				$('#data-posts-container').find('#' + post_id).remove().next('.ui .divider').remove();
				var post_count = $('.comment').length;
			    var new_count = post_count - 1;
			    if ( new_count <= 0 ) {
				    $('#no-post-msg').text('There are no posts to show!');
				    
				    if ( new_count == -1 ) {
					    new_count = 0;
				    }
			    }

				
				$('#num_posts').text(new_count);
				
				
			});
		}
		
		else {
			return false;
		}
	});
	
	$('body').on('mouseenter', '.following-btn', function(e) {
		var btn = $(this);
		var icon = $(this).find('i');
		var text = $(this).text();
		
		$(this).removeClass('green').addClass('red').removeClass('following-btn').addClass('unfollow-btn').html('<i class="remove icon"></i> Unfollow');

	});
	
	$('body').on('mouseleave', '.unfollow-btn', function(e) {
		$(this).removeClass('red').addClass('green').removeClass('following-btn').addClass('following-btn').html('<i class="checkmark icon"></i> Following');
	});
	
	$('body').on('click', '.unfollow-btn', function(e) {
		var btn = $(this);
		var url = 'http://social.mattaltepeter.com/users/unfollow';
		var data = { 'user_id' : $(this).data('user-id') };
		
		$.post(url, data, function(data) {
			console.log(data);
			var json = JSON.parse(data);
			
			if ( json.data ) {
				btn.removeClass('green').removeClass('red').removeClass('following-btn').removeClass('unfollow-btn').addClass('follow-btn').addClass('teal').addClass('follow-btn').html('<i class="plus icon"></i> Follow');
			}
		});
		
	});
	
});

