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
					loadPostItem(post_ids[i]);
				}
			},
			error: function(jqXHR, status, error) {
				alert(jqXHR.responseText);
			}
	});
	
	$("#post_button").click(function(event) {
		event.preventDefault();
		/*
		* This is going to be altered; we eventually want this to only be used to make posts
		* once we're already in a thread.  It's as it is now just to avoid any possible
		* compilation errors or glitches.
		*/
		alert("submit does nothing now; should be used to add replies to threads");
	});
	
	$("#thread_button").click(function(event) {
		event.preventDefault();
		createThread();
	});
	
	var longpress = false, startTime, endTime;
	
	$("#message_board").on('click', '.original_post', function(event) {
		event.preventDefault();
		if (!longpress) 
			alert("this is a thread; everything worked!");
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
					$("#message_board").prepend(p.makeDiv($("#message_board").offsetWidth));
				},
				error: function(jqXHR, status, error) {
					alert(jqXHR.responseText);
			}
		}
	);
}

var loadPostItem = function(id) {
	$.ajax(url_base + "/chatter.php/" + id,
		{type: "GET",
		dataType: "json",
		success: function(post_json, status, jqXHR) {
			var p = new Post(post_json);
			$("#message_board").prepend(p.makeDiv($("#message_board").offsetWidth));
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