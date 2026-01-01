<?php
session_start();
include 'db.php';

$step = $_POST['step'] ?? 'login';

/* ---------- LOGIN ---------- */
if ($step === 'login') {

    $input = trim($_POST['email'] ?? '');
    $inputPassword = $_POST['password'] ?? '';

    if (!$input || !$inputPassword) {
        echo "Missing credentials.";
        exit;
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
        echo "Invalid username/email or password.";
        exit;
    }

    /* ---------- FIRST TIME LOGIN ---------- */
/* ---------- FIRST TIME LOGIN ---------- */
if ($admin['IS_INITIALIZED'] == 0) {
    echo "Please use your email invite link to set up your account.";
    exit;
}   

    // // If there is no temp hash yet, auto-generate one and save it
    // if (empty($admin['TEMP_PASSWORD_HASH'])) {

    //     // 1) generate random temp password (8–10 characters)
    //     $tempPlain = bin2hex(random_bytes(4));

    //     // 2) hash it
    //     $tempHash = password_hash($tempPlain, PASSWORD_DEFAULT);

    //     // 3) save hash in DB
    //     $save = $conn->prepare("
    //         UPDATE Admin
    //         SET TEMP_PASSWORD_HASH = ?
    //         WHERE ID = ?
    //     ");
    //     $save->bind_param("si", $tempHash, $admin['ID']);
    //     $save->execute();

    //     // 4) show the generated password ONCE
    //     echo "Temporary password generated: " . $tempPlain;
    //     exit;
    // }

    // // If temp hash already exists, verify normally
    // if (!password_verify($inputPassword, $admin['TEMP_PASSWORD_HASH'])) {
    //     echo "Invalid temporary password.";
    //     exit;
    // }

    // $_SESSION['setup_admin_id'] = $admin['ID'];
    // echo "setup";
    // exit;}

    /* ---------- NORMAL LOGIN ---------- */
    if (!password_verify($inputPassword, $admin['PASSWORD_HASH'])) {
        echo "Invalid username/email or password.";
        exit;
    }

    $_SESSION['logged_in'] = true;
    $_SESSION['admin_id'] = $admin['ID'];

    echo "success"; // <-- JS redirects to admin-home.php
    exit;
}

/* ---------- SET CREDENTIALS (FIRST LOGIN) ---------- */
if ($step === 'set') {

    if (empty($_SESSION['setup_admin_id'])) {
        echo "Unauthorized action.";
        exit;
    }

    $username = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        echo "All fields are required.";
        exit;
    }

    // username must be unique
    $check = $conn->prepare("SELECT ID FROM Admin WHERE USERNAME = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Username already taken.";
        exit;
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

    // auto-login
    $_SESSION['logged_in'] = true;
    $_SESSION['admin_id'] = $_SESSION['setup_admin_id'];
    unset($_SESSION['setup_admin_id']);

    echo "success";
    exit;
}

/* ---------- RESET PASSWORD ---------- */
if ($step === 'reset') {

    if (empty($_SESSION['reset_admin_id'])) {
        echo "Unauthorized action.";
        exit;
    }

    $newPassword = $_POST['password'] ?? '';

    if (!$newPassword) {
        echo "Password is required.";
        exit;
    }

    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $update = $conn->prepare("
        UPDATE Admin
        SET PASSWORD_HASH = ?,
            RESET_TOKEN_HASH = NULL,
            RESET_TOKEN_EXPIRES = NULL
        WHERE ID = ?
    ");
    $update->bind_param("si", $passwordHash, $_SESSION['reset_admin_id']);
    $update->execute();

// cleanup token
unset($_SESSION['reset_admin_id']);

// show success message once
$_SESSION['password_reset_success'] = true;

echo "reset_success";
exit;
}

echo "Invalid step.";
exit;