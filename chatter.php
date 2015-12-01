<!DOCTYPE html>

<html>
<head>
	<title>Chatter</title>
	<link href="chatter.css" rel="stylesheet" type="text/css">
	
	<script src="http://www.cs.unc.edu/Courses/comp426-f15/jquery-1.11.3.js"
          type="text/javascript"></script>
    <script src="chatter.js" type="text/javascript"></script>
</head>

<body>
	<div class="main">
		<h1 id="main_heading">Chatter</hi>
		<div class="top_area">
			<div class="submit_form">
				<div id="post_heading">
					Post Message
				</div>
				<div id="post_body">
					<textarea id="post_body_form" name="post_body_form" maxlength="1000"></textarea>
				</div>
				<div id="post_bottom">
					<input type="button" id="post_button" value="Submit"> 
				</div>
			</div>
		</div>
		
		<div id="message_board"></div>
		
	</div>
</body>
</html>
