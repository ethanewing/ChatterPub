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


$(document).ready(function() {
	
	$("#post_button").click(function(event) {
			event.preventDefault();
			message_board_div = $("#message_board");
			message = $("#post_body_form").val();
			message_board = new Post(message_board_div, message, message.length);
	});
	
});

var Post = function(message_board, message, message_length) {
	this.message_board = message_board;
//	this.message = message;
	this.message = message.replace(/\r?\n/g, '<br />');
	this.message_length = message_length;
	this.width = message_board.offsetWidth;
	
	this.message_div = $("<div><p>" + this.message + "</p></div>").css({
		position: "relative",
		width: this.width,
		height: "50px",
//		top: this.vert_pos,
		"font-size": Post.FONT_SIZE,
		"margin-top": "5px"
	});
	this.message_div.addClass("live");
	
	message_board.append(this.message_div);
}

Post.HEIGHT = 50;
Post.FONT_SIZE = 14;
