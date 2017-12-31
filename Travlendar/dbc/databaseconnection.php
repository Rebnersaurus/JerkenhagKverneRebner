<?php
	$dbservername = "localhost";
	$dbusername = "root";
	$dbpassword = "";
	$dbname = "travlendar";

	/*
	$dbusername = "joakim_travlenda";
	$dbpassword = "polimi2017";
	$dbname = "joakim_travlendar";
	*/

	$conn = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

	if($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	}

	$conn->query("SET NAMES 'utf8'");
	$conn->query("CHARSET 'utf8'");
?>