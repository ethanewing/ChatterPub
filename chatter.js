/*
 * 
 * TODO:
 * 		create a MESSAGE_BOARD object
 * 		MESSAGE_BOARD object should have a prototype field that keeps track of its length
 * 		the MESSAGE_BOARD object's length will be passed into each POST object we create
 * 
 * This current version still needs a lot of improvement.
 *
 *
 */

var url_base = "http://wwwp.cs.unc.edu/Courses/comp426-f15/users/eewing/Codiad/workspace/cs426/FinalProject";

$(document).ready(function () {
	
	$.ajax(url_base + "/chatter.php",
			{type: "GET",
			dataType: "json",
			success: function(post_ids, status, jqXHR) {
				for (var i = 0; i < post_ids.length; i++) {
					loadPostItem(post_ids[i], 1);
				}
			},
			error: function(jqXHR, status, error) {
				alert(jqXHR.responseText);
			}
	});
	
	$("#post_bottom").on('click', '#reply_button', function(event) {
		event.preventDefault();
		var thread_id = $(".original_post").data('post').thread_id;
		createPost(thread_id, 0);
	});
	
	$("#thread_button").click(function(event) {
		event.preventDefault();
		createThread();
	});
	
	var longpress = false, startTime, endTime;
	
	$("#message_board").on('click', '.original_post', function(event) {
		event.preventDefault();
		if (!longpress) {
			var thread_data = $(this).data('post');
			var thread_id = thread_data.thread_id;
			$(".live").remove();
			
			$.ajax(url_base + "/chatter.php/thread/" + thread_id,
					{
						type: "GET",
						dataType: "json",
						success: function(post_ids, status, jqXHR) {
							for(i = 0; i < post_ids.length; i++) {
								loadPostItem(post_ids[i], 0);
							}
						},
						error: function(jqXHR, status, error) {
							alert(jqXHR.responseText);
						}
			});
			
			if(document.getElementById("homepage_link") == null) {	
				var homepage_link = $("<div><a id='homepage_link' href=" + url_base + "/chatter.html>Back to homepage</a></div>");
				homepage_link.css({
					position: "absolute",
					float: "left",
					"margin-top": "-20px",
					"margin-left": "1.35%"
				})
				$("#message_board").prepend(homepage_link);
			}
			
			if(document.getElementById("reply_button") == null) {
				$("#thread_button").remove();
				var reply_button = $("<input type='button' class='btn btn-info' id='reply_button' value='Reply to thread'>");
				$("#post_bottom").append(reply_button);
			}
		}
	});
	
	$("#message_board").on('mousedown', '.original_post', function(event) {
		startTime = new Date().getTime();
	});
	
	$("#message_board").on('mouseup', '.original_post', function(event) {
		endTime = new Date().getTime();
		longpress = (endTime - startTime < 250) ? false: true;
	});
	
});

var createThread = function() {
	var thread_data = {};
	thread_data['id'] = 0;
	
	$.ajax(url_base + "/chatter.php/" + "createThread",
			{
				type: "POST",
				dataType: "json",
				data: thread_data,
				success: function(thread_json, status, jqXHR) {
					createPost(thread_json.id, 1);
				},
				error: function(jqXHR, status, error) {
					alert(jqXHR.responseText);
				}
			})
}

var createPost = function(thread_id, is_original_post) {
	var post_data = {};
	post_data['id'] = 0;
	post_data['message'] = $("#post_body_form").val();
	post_data['timestamp'] = getDateTime();
	post_data['thread_id'] = thread_id;
	post_data['is_original_post'] = is_original_post;

	$.ajax(url_base + "/chatter.php",
			{
				type: "POST",
				dataType: "json",
				data: post_data,
				success: function(post_json, status, jqXHR) {
					var p = new Post(post_json);
					if(is_original_post == 1)
						$("#message_board").prepend(p.makeDiv($("#message_board").offsetWidth));
					else
						$("#message_board").append(p.makeDiv($("#message_board").offsetWidth));
				},
				error: function(jqXHR, status, error) {
					alert(jqXHR.responseText);
			}
		}
	);
}

// isHomePage determines whether we prepend or append each new Post to the message board
var loadPostItem = function(id, isHomePage) {
	$.ajax(url_base + "/chatter.php/" + id,
		{type: "GET",
		dataType: "json",
		success: function(post_json, status, jqXHR) {
			var p = new Post(post_json);
			if(isHomePage == 1)
				$("#message_board").prepend(p.makeDiv($("#message_board").offsetWidth));
			else
				$("#message_board").append(p.makeDiv($("#message_board").offsetWidth));
		},
		error: function(jqXHR, status, error) {
				alert(jqXHR.responseText);
		}
	});
}

 function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds();
    
    if (hour > 12) {
    	hour -= 12;
    } else if (hour === 0) {
    	hour = 12;
    }
    
    if(month.toString().length == 1) {
        month = '0' + month;
    }
    if(day.toString().length == 1) {
        day = '0' + day;
    }   
    if(hour.toString().length == 1) {
        hour = '0' + hour;
    }
    if(minute.toString().length == 1) {
        minute = '0' + minute;
    }
    if(second.toString().length == 1) {
        second = '0' + second;
    }   
    var dateTime = hour + ':' + minute + ':' + second + ' ' + month + '/' + day + '/' + year;   
    
    return dateTime;
}
