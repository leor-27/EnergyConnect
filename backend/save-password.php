<?php
session_start();
if(!isset($_SESSION["temp_login"])) die("Unauthorized.");

if(!isset($_POST["newpass"]) || empty($_POST["newpass"])) {
    die("Password cannot be empty.");
}

$newPass = password_hash($_POST["newpass"], PASSWORD_BCRYPT);

// Save to simulated DB
file_put_contents("perm.txt", $newPass);  // permanent password
if(file_exists("temp.txt")) unlink("temp.txt"); // remove temp password

unset($_SESSION["temp_login"]);

echo "<script>alert('Password Set Successfully!');</script>";
echo "<script>window.location='/EnergyConnect/admin-home.html';</script>";
?>
