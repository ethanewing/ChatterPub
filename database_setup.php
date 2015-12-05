<?php

  /*
   * We're just hardcoding this info in at the moment so that we have something to work with.
   */

	$conn = new mysqli("classroom.cs.unc.edu", "eewing", "CH@ngemenow99Please!eewing", "eewingdb");
	
	$conn->query("drop table if exists Post");

	$conn->query("create table Post (id int auto_increment, message text, time date, primary key (id))");
							
	$message1 = "this is a message";
	$message2 = "this is another message";
	
	$conn->query("insert into Post values (0, '" . $message1 . "', " . "'1990-10-07')");
	$conn->query("insert into Post values (0, '" . $message2 . "', " . "'1811-08-22')");

?>
