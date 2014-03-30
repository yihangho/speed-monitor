<?php
$mysql = new mysqli("localhost", "root", "root", "speedtest");

if ($mysql->connect_errno) {
	error_log($mysql->connect_error);
	die();
}
?>