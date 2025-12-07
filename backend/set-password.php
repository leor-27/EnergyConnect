<?php
session_start();
if(!isset($_SESSION["temp_login"])) die("Unauthorized.");
?>
<form action="save-password.php" method="POST">
    <h2>Create your permanent password</h2>
    <input type="password" name="newpass" placeholder="New password" required><br>
    <button type="submit">Save</button>
</form>