<?php
session_start();

if (
    empty($_SESSION['logged_in']) ||
    empty($_SESSION['admin_id'])
) {
    header("Location: index.php");
    exit;
}

include 'backend/db.php';

if (!isset($_GET['id'])) {
    header("Location: admin-add-programs.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM Program WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin-add-programs.php?deleted=1");
exit;