<?php
$host = "localhost";
$user = "root";
$password = "cscpeboy12";
$dbname = "energyfm_cms";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
?>