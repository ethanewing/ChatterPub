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
					load_post_item(post_ids[i]);
				}
			},
			error: function(jqXHR, status, error) {
				alert(jqXHR.responseText);
			}
	});
	
	$("#post_button").click(function(event) {
		event.preventDefault();

		var post_data = {};
		post_data['id'] = 0;
		post_data['message'] = $("#post_body_form").val();
		post_data['timestamp'] = '2015-04-02';
		
		$.ajax(url_base + "/chatter.php",
				{type: "POST",
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
	});
	
	
});

var load_post_item = function(id) {
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

var Post = function(message_board, message, message_length) {
	
	if(document.getElementById('post_body_form').value == ""){
		alert('You cannot post a blank message'); 
	} else {
		this.message_board = message_board;
	//	this.message = message;
		this.message = message.replace(/\r?\n/g, '<br />');
		this.message_length = message_length;
		this.width = message_board.offsetWidth;
		
		this.message_div = $("<div><p>" + this.message + "</p></div>").css({
			position: "relative",
			width: this.width,
			"min-height": "50px",
	//		top: this.vert_pos,
			"font-size": Post.FONT_SIZE,
			"margin-top": "5px"
		});
		this.message_div.addClass("live");
		
		message_board.prepend(this.message_div);
		//$(".live").show( selectedEffect, options, 500);
		document.getElementById('post_body_form').value = "";
		document.forms['submit_form'].elements['post_body_form'].focus();
	}
}



$("input").keypress(function(event) {
    if (event.which == 13) {
        event.preventDefault();
        $('#post_body_form').submit();
    }
});




Post.HEIGHT = 50;
Post.FONT_SIZE = 14;


function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
        var second = '0'+second;
    }   
    var dateTime = year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second;   
     return dateTime;
}
