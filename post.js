var Post = function(post_json) {
  this.id = post_json.id;
  this.message = post_json.message;
  this.timestamp = post_json.timestamp;
  this.thread_id = post_json.thread_id;
  this.is_original_post = post_json.is_original_post;
  this.rating = post_json.rating;
}

Post.prototype.makeDiv = function(width, p_id) {
	if(this.is_original_post == 1){
		var post_div = $("<div id=" + p_id + ">" + this.message + "<p></p><br><p></p></div>").css({
		  position: "relative",
		  "min-height": "210px",
		  width: "23.4375%",
		  "font-size": Post.FONT_SIZE,
		  "margin-top": "35px",
		  float: "left",
		  "margin-left": "1.35%"
		});
	} else {
		var post_div = $("<div id=" + p_id + ">" + this.message + "<p></p><br><p></p></div>").css({
		  position: "relative",
		  "min-height": "150px",
		  width: "73.0125%",
		  "font-size": Post.FONT_SIZE,
		  "margin-top": "35px",
		  float: "right",
		  "margin-left": "1.35%",
		});
	}
	
	var rating_div = $("<div id='rating" + p_id + "'>Rating: " + this.rating + "</div>").css({
		position: "absolute",
		bottom: "0px",
		"font-size": Post.FONT_SIZE_OF_DATE
	});
	
	var date_div = $("<div>" + this.timestamp + "</div>").css({
		position: "absolute",
		bottom: "0px",
		"font-size": Post.FONT_SIZE_OF_DATE,
		right: "5px"
	});
	
	var rating_adjustment_div = $("<div></div>").css({
		position: "absolute",
		float: "left",
		"margin-top": "-28px",
		"margin-left": "-10px",
		"background-color": "rgba(123,175,212,.8)",
		width: "100px",
		height: "25px",
		border: "thin solid rgb(100,100,100)",
		"border-radius": "10px"
	})
	
	var upvote_div = $("<div class='upvote'><strong>+</strong></div>").css({
		position: "absolute",
		float: "left",
		left: "15%",
	});
	
	var downvote_div = $("<div class='downvote'><strong>-</strong></div>").css({
		positon: "absolute",
		float: "right",
		"margin-right": "15%",
	})
	
	rating_div.data('p_id', p_id);
	upvote_div.data('p_id', p_id);
	downvote_div.data('p_id', p_id);
	
	rating_adjustment_div.append(upvote_div);
	rating_adjustment_div.append(downvote_div);
	
	post_div.append(rating_div);
	post_div.append(date_div);
	post_div.prepend(rating_adjustment_div);
	
	if(this.is_original_post == 1)
		post_div.addClass('original_post');
	post_div.addClass('live');
	post_div.data('post', this);
	
	return post_div;
};

Post.FONT_SIZE = 18;
Post.FONT_SIZE_OF_DATE = 16;
