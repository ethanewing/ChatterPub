<?php

	require_once('chatter_orm.php');
	
	$path_components = explode('/', $_SERVER['PATH_INFO']);
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		// GET means eiher instance look up, index generation, or deletion
		
		header("Content-type: application/json");
		print(json_encode(Todo::getAllIDs()));
		exit();
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POST') {
		// This should only add new posts; we don't want to allow post editing for now.
		
		// Creating a new Post item
		
		// Validate values
		if(!isset($_REQUEST['id'])) {
			header("HTTP/1.0 400 Bad Request");
			print("Missing id");
			exit();
		}
		
		$message = trim($_REQUEST['message']);
		if($message == '') {
			header("HTTP/1.0 400 Bad Request");
			print("Invalid message");
			exit();
		}
		
		$time = null;
		if(isset($_REQUEST['time'])) {
			$time = trim($_REQUEST['time']);
		}
		
		// Create new Post item via ORM
		$new_post = Post::create($id, $message, $time);
		
		// Report if failed
		if ($new_post == null) {
			header("HTTP/1.0 500 Server Error");
			print("Server couldn't create new post.");
			exit();
			
		}
		
		//Generate JSON encoding of new Todo
		header("Content-type: application/json");
		print($new_post->getJSON());
		exit();
		
	}
	
	// If here then none of the above applied and URL could not be interpreted
	// with respect to ReSTful conventions.
	header("HTTP/1.0 400 Bad Request");
	print("Did not understand URL");

?>
