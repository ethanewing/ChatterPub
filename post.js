var Post = function(post_json) {
  this.id = post_json.id;
  this.message = post_json.message;
  this.timestamp = post_json.timestamp;
  this.thread_id = post_json.thread_id;
  this.is_original_post = post_json.is_original_post;
}

Post.prototype.makeDiv = function(width) {
	var post_div = $("<div>" + this.message + "</div>").css({
	  position: "relative",
	  height: "100px",
	  width: "23.4375%",
	  "font-size": Post.FONT_SIZE,
	  "margin-top": "5px",
	  float: "left",
	  "margin-left": "1.35%"
	});
	
	var date_div = $("<div>" + this.timestamp + "</div>").css({
		position: "absolute",
		bottom: "0px",
		right: "5px"
	})
	post_div.append(date_div);
	
	if(this.is_original_post)
		post_div.addClass('original_post');
	post_div.addClass('live');
	post_div.data('post', this);
	
	return post_div;
};

Post.FONT_SIZE = 14;

// not sure if we'll actually need this object yet.
var Thread = function(thread_json) {
	this.id = thread_json.id;
	this.original_post = thread_json.original_post;
}