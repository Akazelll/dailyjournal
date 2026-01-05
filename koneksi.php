<?php 
date_default_timezone_set('Asia/Jakarta');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latihan_pbw";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>