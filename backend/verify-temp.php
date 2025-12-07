<?php
session_start();

$email = $_POST["email"] ?? '';
$pass  = $_POST["password"] ?? '';

// Use __DIR__ to ensure correct file path
$storedTemp = file_exists(__DIR__ . "/temp.txt") ? file_get_contents(__DIR__ . "/temp.txt") : '';
$storedPass = file_exists(__DIR__ . "/perm.txt") ? file_get_contents(__DIR__ . "/perm.txt") : '';

// Permanent password check
if($storedPass && password_verify($pass, $storedPass)){
    echo "OK"; 
    exit;
}

// Temporary password check
if($storedTemp && password_verify($pass, $storedTemp)){
    $_SESSION["temp_login"] = true;
    echo "SET_PASSWORD"; 
    exit;
}

echo "Incorrect password or expired temporary password.";
?>
