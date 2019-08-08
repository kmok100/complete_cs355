<?php
	$servername = "mars.cs.qc.cuny.edu";
  //First 2 letters of last name, then first 2 letters of first name, then last 4 of qc id
	$username = "zada7910";
  //qc id
	$password = "23447910";
  //same as username
	$dbname = "zada7910";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
?>