<?php
session_start();

if (
    empty($_SESSION['logged_in']) ||
    empty($_SESSION['admin_id']) ||
    (int)$_SESSION['admin_id'] !== SUPER_ADMIN_ID
) {
    header("Location: admin-home.php?error=unauthorized");
    exit;
}

include 'backend/db.php';
include 'backend/config.php';

if (!isset($_GET['id'])) {
    header("Location: admin-audio-broadcasts.php");
    exit;
}

$id = (int)$_GET['id'];

if ($id <= 0) {
    header("Location: admin-audio-broadcasts.php?error=invalid");
    exit;
}

$stmt = $conn->prepare("
    DELETE FROM Audio_Broadcast_Log
    WHERE ID = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: admin-audio-broadcasts.php?deleted=1");
} else {
    header("Location: admin-audio-broadcasts.php?error=notfound");
}

exit;
