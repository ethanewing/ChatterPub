<?php

	/*
	 * Note: as of now, the user database and class are not configured to account for anything other than
	 * an ID.  We'll want to log other information to account for user authentication, whether the user
	 * is anonymous or not (should we choose to have user profiles).  So as of now this is unfinished.
	 */

	class Post {
		private $id;
		private $message;
		private $time;
		private $userid;
		private $threadid;
		
		public static function create($message, $time, $userid, $threadid) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if ($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
			else {
				$result = $conn->query("insert into Post values (0, '" . $mesage . "', '" . $time . "', "
										. $userid . ", " . $threadid . ")");
				if($result) {
					$newid = $conn->insert_id;
					return new Post($newid, $message, $time, $userid, $threadid);
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
									$post_info['time'], 
									$post_info['userid'],
									$post_infor['threadid']);
				}
				
				return null;
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
		
		private function __construct($id, $message, $time, $userid, $threadid) {
			$this->$id = $id;
			$this->$message = $message;
			$this->$time = $time;
			$this->$userid = $userid;
			$this->$threadid = $threadid;
		}
		
		public function getID() {
			return $this->$id;
		}
		
		public function getMessage() {
			return $this->$message;
		}
		
		public function getTime() {
			return $this->$time;
		}
		
		public function getUser() {
			/*
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
			else {
				$result = $conn->query("select * from User where id = " . )
			}
			*/
			return User::findByID($this->$userid);
		}
		
		public function getThread() {
			return Thread::findByID($this->$threadid);
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
	
	class Thread {
		private $id;
		private $forumid;
		
		public static function create($forumid) {
			$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
			if ($conn->connect_errno)
				echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
			else {
				$result = $conn->query("insert into Thread values (0, " . $forumid . ")");
				if($result) {
					$newid = $conn->insert_id;
					return new Thread($newid, $forumid);
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
					return new Post($thread_info['id'], 
									$thread_info['forumid']);
				}
				
				return null;
			}
		}
		
		private function __construct($id, $forumid) {
			$this->$id = $id;
			$this->$forumid = $forumid;
		}
		
		public function getID() {
			return $this->$id;
		}
		
		public function getForum() {
			return Forum::findByID($this->$forumid);
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
