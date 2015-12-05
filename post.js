var Post = function(post_json) {
  this.id = post_json.id;
  this.message = post_json.id;
  this.time = post_json.time;
}

Post.prototype.makeDiv = function(width) {
  var post_div = $("<div>" + this.message + "</div>").css({
	  position: "relative",
	  height: "50px",
	  width: width,
	  "font-size": Post.FONT_SIZE,
	  "margin-top": "5px"
	});
	
	post_div.addClass("live");
	post_div.data('post', this);
	
	return post_div;
};

Post.FONT_SIZE = 14;
