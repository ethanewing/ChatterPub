<?php

	require_once('chatter_orm.php');
	
	$path_components = explode('/', $_SERVER['PATH_INFO']);
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		// GET means eiher instance look up, index generation, or deletion.
		// However, for this app, we only care about instance look up.
		
		// This is for loading threads
		if((count($path_components) >= 3) && ($path_components[1] == "thread") && ($path_components[2] != "")) {
			// Interpret <thread_id> as integer
			$thread_id = intval($path_components[2]);
			
			// Get all post IDs in thread
			$thread_content = Thread::getAllPostIDs($thread_id);
			
			if($thread_content == null) {
				// Thread not found
				header("HTTP/1.0 404 Not Found");
				print("Thread id: " . $thread_id . " not found.");
				exit();
			}
			
			// Normal lookup; generate JSON encoding as response
			header("Content-type: application/json");
			print(json_encode($thread_content));
			exit();
		}
		// The following matches instance URL in form /chatter.php/[id]
		else if ((count($path_components) >= 2) && ($path_components[1] != "")) {
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
		
		// Loading homepage
		header("Content-type: application/json");
		print(json_encode(Post::getAllOriginalPosts()));
		exit();
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POST') {
		// This should only add new posts and new threads
		
		// Create a new Thread
		if(count($path_components) == 2 && $path_components[1] != "") {
			$thread_id = intval($_REQUEST['id']);
			
			$new_thread = Thread::create();
			
			// Generate JSON encoding of new Thread
			header("Content-type: application/json");
			print($new_thread->getJSON());
			exit();
		}
		
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
		
		$thread_id = false;
		if(isset($_REQUEST['thread_id'])) {
			$thread_id = intval($_REQUEST['thread_id']);
		}
		else {
			header("HTTP/1.0 400 Bad Request");
			print("Invalid thread id");
			exit();
		}
		
		$is_original_post = false;
		if(isset($_REQUEST['is_original_post'])) {
			$is_original_post = intval($_REQUEST['is_original_post']);
		}
		else {
			header("HTTP/1.0 400 Bad Request");
			print("Do not know if original post");
			exit();
		}
		
		
		$rating = false;
		if(isset($_REQUEST['rating']))
			$rating = intval($_REQUEST['rating']);
		else {
			header("HTTP/1.0 400 Bad Request");
			print("Do not know if original post");
			exit();
		}
		
		// Create new Post item via ORM
		$new_post = Post::create($message, $timestamp, $thread_id, $is_original_post, $rating);
		
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
