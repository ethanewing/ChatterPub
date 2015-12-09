var Post = function(post_json) {
  this.id = post_json.id;
  this.message = post_json.message;
  this.timestamp = post_json.timestamp;
  this.thread_id = post_json.thread_id;
  this.is_original_post = post_json.is_original_post;
  this.rating = post_json.rating;
}

Post.prototype.makeDiv = function(width) {
	var post_div = $("<div>" + this.message + "</div>").css({
	  position: "relative",
	  height: "210px",
	  width: "23.4375%",
	  "font-size": Post.FONT_SIZE,
	  "margin-top": "5px",
	  float: "left",
	  "margin-left": "1.35%"
	});
	
	var date_div = $("<div>" + this.timestamp + " " + this.rating + "</div>").css({
		position: "absolute",
		bottom: "0px",
		"font-size": Post.FONT_SIZE_OF_DATE,
		right: "5px"
	});
	post_div.append(date_div);
	
	if(this.is_original_post == 1)
		post_div.addClass('original_post');
	post_div.addClass('live');
	post_div.data('post', this);
	
	return post_div;
};

Post.FONT_SIZE = 18;
Post.FONT_SIZE_OF_DATE = 16;
