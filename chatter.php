<?php

	require_once('chatter_orm.php');
	
	$path_components = explode('/', $_SERVER['PATH_INFO']);
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		// GET means eiher instance look up, index generation, or deletion.
		// However, for this app, we only care about instance look up.
		
		// The following matches instance URL in form /chatter.php/[id]
		if ((count($path_components) >= 2) && ($path_components[1] != "")) {
			// Interpret <id> as integer
			$post_id = intval($path_components[1]);
			
			// Look up object via ORM
			$post = Post::findByID($post_id);

			if ($post == null) {
				// Post not found.
				header("HTTP/1.0 404 Not Found");
				print("Post id: " . $post_id . " not found.");
				exit();
			}
			
			// Normal lookup; generate JSON encoding as response
			header("Content-type: application/json");
			print($post->getJSON());
			exit();
		}
		
		header("Content-type: application/json");
		print(json_encode(Post::getAllIDs()));
		exit();
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POST') {
		// This should only add new posts; we don't want to allow post editing.
		
		// Creating a new Post item
		
		$message = trim($_REQUEST['message']);
		if($message == '') {
			header("HTTP/1.0 400 Bad Request");
			print("Invalid message");
			exit();
		}
		
		$timestamp = null;
		if(isset($_REQUEST['timestamp'])) {
			$timestamp = trim($_REQUEST['timestamp']);
		}
		
		// Create new Post item via ORM
		$new_post = Post::create($message, $timestamp);
		
		// Report if failed
		if ($new_post == null) {
			header("HTTP/1.0 500 Server Error");
			print("Server couldn't create new post.");
			exit();
		}
		
		//Generate JSON encoding of new Post
		header("Content-type: application/json");
		print($new_post->getJSON());
		exit();
		
	}
	
	// If here then none of the above applied and URL could not be interpreted
	// with respect to ReSTful conventions.
	header("HTTP/1.0 400 Bad Request");
	print("Did not understand URL");

?>
