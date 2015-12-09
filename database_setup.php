<?php

	$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
	
	$conn->query("drop table if exists Post");
	$conn->query("drop table if exists Thread");

	$conn->query("create table Thread (id int auto_increment, primary key (id))");
	$conn->query("create table Post (id int auto_increment, message text, timestamp text, thread_id int,
				is_original_post int, rating int, primary key (id), foreign key (thread_id) references Thread (id))");

?>
