<?php
include 'backend/db.php';

$email = trim($_POST['email'] ?? '');

if (!$email) {
    die("Email required");
}

// Check email exists in Admin table and not yet initialized
$stmt = $conn->prepare("SELECT ID FROM Admin WHERE EMAIL = ? AND IS_INITIALIZED = 0");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    // Do not reveal whether the email exists
    echo "If this email exists, an invite will be sent.";
    exit;
}

// Generate 64-character token
$token = bin2hex(random_bytes(32));
$tokenHash = hash('sha256', $token);

// Set 1 hour expiration
$expires = date('Y-m-d H:i:s', time() + 3600);

// Save hash + expiration in DB
$update = $conn->prepare("
    UPDATE Admin
    SET INVITE_TOKEN_HASH = ?, INVITE_TOKEN_EXPIRES = ?
    WHERE ID = ?
");
$update->bind_param("ssi", $tokenHash, $expires, $row['ID']);
$update->execute();

// Build invite link (token included once)
$inviteLink = "http://localhost:8000/setup.php?token=" . urlencode($token);

// For dev/testing purposes
echo "DEV TOKEN: " . $inviteLink;

// In production, send email instead
// mail($email, "Your EnergyConnect Admin Invite", "Click here: $inviteLink");

exit;
