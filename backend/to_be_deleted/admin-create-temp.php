<?php
session_start();

// Generate random secure temp password
function generateTempPassword($length = 12) {
    return bin2hex(random_bytes($length / 2)); // e.g., a49fd7f21c
}

$tempPass = generateTempPassword();
$hashedTemp = password_hash($tempPass, PASSWORD_BCRYPT);

// Store hashed temp password (simulate DB)
file_put_contents("temp.txt", $hashedTemp);

// Show the temp password for testing
echo "<h2>Temporary Password Generated!</h2>";
echo "Temp password (visible only now): <b>$tempPass</b><br>";
echo "Hashed password stored (simulated DB): $hashedTemp<br>";
?>
