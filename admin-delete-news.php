<?php
include 'backend/db.php';

if (!isset($_GET['id'])) {
    header("Location: admin-home.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM News WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin-home.php?deleted=1");
    exit;
} else {
    echo "Error deleting record.";
}