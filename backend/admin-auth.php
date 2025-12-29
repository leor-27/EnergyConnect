<?php
session_start();
include 'db.php';

$step = $_POST['step'] ?? 'login';

if ($step === 'login') {

    $input = trim($_POST['email'] ?? '');
    $inputPassword = $_POST['password'] ?? '';

    if (!$input || !$inputPassword) {
        die("Missing credentials.");
    }

    $stmt = $conn->prepare("
        SELECT ID, PASSWORD_HASH, TEMP_PASSWORD_HASH, IS_INITIALIZED
        FROM Admin
        WHERE EMAIL = ? OR USERNAME = ?
        LIMIT 1
    ");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$admin = $result->fetch_assoc()) {
        die("Invalid login.");
    }

    /* FIRST TIME LOGIN */
    if ($admin['IS_INITIALIZED'] == 0) {

        if (!password_verify($inputPassword, $admin['TEMP_PASSWORD_HASH'])) {
            die("Invalid temporary password.");
        }

        $_SESSION['setup_admin_id'] = $admin['ID'];
        $_SESSION['show_set_credentials'] = true;

        header("Location: ../index.php");
        exit;
    }

    /* NORMAL LOGIN */
    if (!password_verify($inputPassword, $admin['PASSWORD_HASH'])) {
        die("Invalid username/email or password.");
    }

    $_SESSION['logged_in'] = true;
    $_SESSION['admin_id'] = $admin['ID'];

    header("Location: ../admin-home.php");
    exit;
}

if ($step === 'set') {

    if (empty($_SESSION['setup_admin_id'])) {
        die("Unauthorized.");
    }

    $username = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        die("All fields are required.");
    }

    // check username uniqueness
    $check = $conn->prepare("SELECT ID FROM Admin WHERE USERNAME = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Username already taken.");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $update = $conn->prepare("
        UPDATE Admin
        SET USERNAME = ?, 
            PASSWORD_HASH = ?, 
            TEMP_PASSWORD_HASH = NULL,
            IS_INITIALIZED = 1
        WHERE ID = ?
    ");
    $update->bind_param("ssi", $username, $passwordHash, $_SESSION['setup_admin_id']);
    $update->execute();

    // auto login
    $_SESSION['logged_in'] = true;
    $_SESSION['admin_id'] = $_SESSION['setup_admin_id'];
    unset($_SESSION['setup_admin_id']);

    header("Location: ../admin-home.php");
    exit;
}