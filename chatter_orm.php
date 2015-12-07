<?php

	/*
	 * Note: as of now, the user database and class are not configured to account for anything other than
	 * an ID.  We'll want to log other information to account for user authentication, whether the user
	 * is anonymous or not (should we choose to have user profiles).  So as of now this is unfinished.
	 */

	class Post {
		private $id;
		private $message;
		private $timestamp;
		private $thread_id;
		private $is_original_post;  // binary 0 or 1 value
		
		public static function create($message, $timestamp, $thread_id, $is_original_post) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if ($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
			else {
				$result = $conn->query("insert into Post values (0, '" . $conn->real_escape_string($message) . "', '" . 
						$timestamp . "', " . $thread_id . ", " . $is_original_post . ")");
				if($result) {
					$newid = $conn->insert_id;
					return new Post($newid, $message, $timestamp, $thread_id, $is_original_post);
				}
				
				return null;
			}
		}
		
		public static function findByID($id) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select * from Post where id = " . $id);
				if($result) {
					if($result->num_rows == 0)
						return null;
						$post_info = $result->fetch_assoc();
						return new Post($post_info['id'], 
										$post_info['message'],
										$post_info['timestamp'],
										$post_info['thread_id'],
										$post_info['is_original_post']);
				}
				
				return null;
			}
		}
		
		public static function getAllIDs() {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select id from Post");
				$id_array = array();
				
				if ($result) {
					while ($next_row = $result->fetch_array()) {
						$id_array[] = intval($next_row['id']);
					}
				}
				return $id_array;
			}
		}
		
		public static function getAllOriginalPosts() {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select id from Post where is_original_post = 1");
				$id_array = array();
				
				if ($result) {
					while ($next_row = $result->fetch_array()) {
						$id_array[] = intval($next_row['id']);
					}
				}
				return $id_array;
			}
		}
		
		/* Used to get multiple Post objects */
		public static function getRange($start, $end) {
			if ($start < 0) {
				if ($end > $start)
					return null;
				$direction = "DESC";
				$start *= -1;
				$end *= -1;
			} 
			else {
				if ($end < $start)
					return null;
				$direction = "ASC";
			}
			
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select id from Post order by id " . $direction);
				$posts = array();
			
				if ($result) {
					for ($i = 1; $i < $start; $i++)
						$result->fetch_row();
					
					for ($i = $start; $i <= $end; $i++) {
						$next_row = $result->fetch_row();
						if ($next_row)
							$posts[] = Post::findByID($next_row[0]);
					}
				}
			}
			
			return $posts;
		}
		
		private function __construct($id, $message, $timestamp, $thread_id, $is_original_post) {
			$this->id = $id;
			$this->message = $message;
			$this->timestamp = $timestamp;
			$this->thread_id = $thread_id;
			$this->is_original_post = $is_original_post;
		}
		
		public function getID() {
			return $this->id;
		}
		
		public function getMessage() {
			return $this->message;
		}
		
		public function getTime() {
			return $this->timestamp;
		}
		
		public function getThreadID() {
			return $this->thread_id;
		}
		
		public function getIsOriginalPost() {
			return $this->is_original_post;
		}
		
		public function getJSON() {
			$json_obj = array('id' => $this->id,
					'message' => $this->message,
					'timestamp' => $this->timestamp,
					'thread_id' => $this->thread_id,
					'is_original_post' => $this->is_original_post);
			return json_encode($json_obj);
		}

	}
	
	class Thread {
		private $id;
		
		public static function create() {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if ($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
			else {
				$result = $conn->query("insert into Thread values (0)");
				if($result) {
					$newid = $conn->insert_id;
					return new Thread($newid);
				}
				
				return null;
			}
		}
		
		public static function findByID($id) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select * from Thread where id = " . $id);
				if($result) {
					if($result->num_rows == 0)
						return null;
					$thread_info = $result->fetch_assoc();
					return new Post($thread_info['id']);
				}
				
				return null;
			}
		}
		
		public static function getAllPostIDs($thread_id) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select id from Post where thread_id = " . $thread_id);
				$id_array = array();
				
				if ($result) {
					while ($next_row = $result->fetch_array()) {
						$id_array[] = intval($next_row['id']);
					}
				}
				return $id_array;
			}
		}
		
		private function __construct($id) {
			$this->id = $id;
		}
		
		public function getID() {
			return $this->id;
		}
		
		public function getJSON() {
			$json_obj = array('id' => $this->id);
			return json_encode($json_obj);
		}
	}
	
	class User {
		private $id;
		
		public static function create() {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if ($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
			else {
				$result = $conn->query("insert into User values (0)");
				if($result) {
					$newid = $conn->insert_id;
					return new User($newid);
				}
				
				return null;
			}
		}
		
		public static function findByID($id) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select * from User where id = " . $id);
				if($result) {
					if($result->num_rows == 0)
						return null;
					$user_info = $result->fetch_assoc();
					return new User($user_info['id']);
				}
				
				return null;
			}
		}
		
		private function __construct($id) {
			$this->$id = $id;
		}
		
		public function getID() {
			return $this->$id;
		}
	}
	
	
	class Forum {
		private $id;
		private $name;
		
		public static function create($name) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if ($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
			else {
				$result = $conn->query("insert into Thread values (0, '" . $name . "')");
				if($result) {
					$newid = $conn->insert_id;
					return new Forum($newid, $name);
				}
				
				return null;
			}
		}
		
		public static function findByID($id) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ")" . $conn->connect_error;
			else {
				$result = $conn->query("select * from Forum where id = " . $id);
				if($result) {
					if($result->num_rows == 0)
						return null;
					$forum_info = $result->fetch_assoc();
					return new Forum($forum_info['id'], 
									$forum_info['name']);
				}
				
				return null;
			}
		}
		
		private function __construct($id, $name) {
			$this->$id = $id;
			$this->$name = $name;
		}
		
		public function getID() {
			return $this->$id;
		}
		
		public function getName() {
			return $this->$id;
		}
	}

?>