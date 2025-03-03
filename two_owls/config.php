<?php
// Database connection settings
$host = 'localhost';
$username = 'usxahvrfz4bgm';
$password = 'cW`1#^h7c#j6';
$database = 'db7ygeqedh20ty';

// Create a connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} else {
}
?>