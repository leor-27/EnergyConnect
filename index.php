<?php
include 'backend/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy FM 106.3</title>
    <link href="frontend/css/landing-page.css" rel="stylesheet">
    <script src = "frontend/js/index.js"></script>
</head>
<body class="landing-page">

    <div class="landing-page-header">
        <div class="header-logo-row">
            <a href="index.php">
                <img src="frontend/images/logo.png" alt="Energy FM 106.3 Naga Logo" class="logo-landing-page">
            </a>
            <h2 class="station-title">Energy FM Naga</h2>
        </div>

        <div class="header-line"></div>
    </div>

    <h1 class="welcome-title">Welcome to <span>ENERGYCONNECT</span></h1>

    <div class="continue">

        <!-- shown first-->
        <section id="main-continue" class="continue-section">
            <h1>Continue as</h1>

            <div class="button-row">
                <a href="home.php" class="user-button">a User</a>
                <button id="show-admin" class="admin-button" type="button">an Admin</button>
            </div>
        </section>

        <!-- admin login (hidden at first) -->
        <section id="admin-login" class="admin-section" style="display:none;">
            <h1 id="form-title">Sign In</h1>

            <form id="admin-form" class="admin-form">

            <label id="name-label" style="display: none;">Name</label> <!-- hidden at first also -->
            <input type="text" id="name" style="display:none;">

            <label id="input-label">Email / Username</label>
            <input type="text" id="email">

            <label id="password-label">Password</label>
            <input type="password" id="password">

            <div class="login">
                <button type="submit" class="login-button" id="login-btn">Login</button>
            </div>

            <p class="google-login" id="google-login">Login using   
                <a href="https://accounts.google.com/signin">
                    <img src="frontend/images/google.png" class="google-icon" alt="Google Icon">
                </a>
            </p>
        </form>
    </section>
</body>
</html>