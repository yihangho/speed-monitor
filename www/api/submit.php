<?php
/*
Send a post request to current file with the following parameters:
timestamp - integer
ping - integer
dl - float
ul - float
*/

// Make sure that request is supported, that is, it must a POST request.
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	echo "Request method unsupported. Please send a POST request instead.";
	die();
}

$mysql = new mysqli("localhost", "root", "root", "speedtest");

// Extract and sanitize input values
$timestamp = $mysql->escape_string(intval($_POST["timestamp"]));
$ping      = $mysql->escape_string(floatval($_POST["ping"]));
$dl        = $mysql->escape_string(floatval($_POST["dl"]));
$ul        = $mysql->escape_string(floatval($_POST["ul"]));

$mysql->query("INSERT INTO speedtest (`ts`, `ping`, `dl`, `ul`) VALUES ('$timestamp', '$ping', '$dl', '$ul')");
?>