document.addEventListener("DOMContentLoaded", () => {
    const forgotWrapper = document.getElementById("forgot-wrapper");
    const form = document.getElementById("admin-form");
    const stepField = document.getElementById("step");
    const inputField = document.getElementById("email");
    const passwordField = document.getElementById("password");
    const button = document.getElementById("login-btn");
    const inputLabel = document.getElementById("input-label");
    const formTitle = document.getElementById("form-title");
    const requestAccessWrapper = document.querySelector(".request-access");

    forgotWrapper.style.display = "none";

    let step = "login";

    /* Show admin login */
    document.getElementById("show-admin").addEventListener("click", () => {
        document.getElementById("main-continue").style.display = "none";
        const adminBox = document.getElementById("admin-login");
        adminBox.style.display = "block";

        document.querySelector(".continue").style.height = "450px";
        adminBox.scrollIntoView({ behavior: "smooth" });

        step = "login";
        stepField.value = "login";

        passwordField.disabled = false;
        passwordField.style.display = "block";
        document.getElementById("password-label").style.display = "block";

        button.innerText = "Continue";
        forgotWrapper.style.display = "block";
        requestAccessWrapper.style.display = "block";
    });

    /* Form submission */
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        /* ---------- REQUEST ACCESS ---------- */
if (step === "request") {

    fetch("request-invite.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(response => {

        // ❌ NOT A VALID LINK → NO TAB
        if (!response.startsWith("http")) {
            alert(response);
            return;
        }

        // ✅ VALID LINK → OPEN TAB
        const emailTab = window.open("", "_blank");

        emailTab.document.write(`
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Invite – EnergyConnect</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    padding: 40px;
}
.email {
    max-width: 600px;
    background: #ffffff;
    margin: auto;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
h2 { color: #4e1e86; }
a.button {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    background: #4e1e86;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}
.footer {
    margin-top: 30px;
    font-size: 12px;
    color: #777;
}
</style>
</head>
<body>
<div class="email">
    <h2>You're invited as an Admin</h2>
    <p>You have been invited to set up your admin account for <strong>EnergyConnect</strong>.</p>
    <p>Click the button below to set your credentials:</p>

    <a class="button" href="${response}">
        Set Up Admin Account
    </a>

    <p style="margin-top:20px;">
        Or copy and paste this link into your browser:<br>
        <small>${response}</small>
    </p>

    <div class="footer">
        This is a development email preview.<br>
        In production, this will be sent via email.
    </div>
</div>
</body>
</html>
        `);

        emailTab.document.close();
    })
    .catch(err => console.error(err));

    return;
}

        /* ---------- LOGIN / SET ---------- */
        fetch("backend/admin-auth.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            if (response === "success") {
                window.location.href = "admin-home.php";
            } else if (response === "setup") {
                showSetCredentials();
            } else {
                alert(response);
            }
        })
        .catch(err => console.error(err));
    });

    /* ---------- SET CREDENTIALS UI ---------- */
    window.showSetCredentials = function () {
        step = "set";
        stepField.value = "set";

        formTitle.innerText = "Set Your Credentials";
        inputLabel.innerText = "Username";

        inputField.value = "";
        passwordField.value = "";
        button.innerText = "Save Credentials";

        document.querySelector(".continue").style.height = "410px";
        forgotWrapper.style.display = "none";
        requestAccessWrapper.style.display = "none";
    };

    /* ---------- REQUEST ACCESS UI ---------- */
    document.getElementById("request-access").addEventListener("click", (e) => {
        e.preventDefault();

        step = "request";
        stepField.value = "request";

        formTitle.innerText = "Admin Sign In";
        inputLabel.innerText = "Email Address";

        inputField.value = "";
        passwordField.value = "";

        passwordField.style.display = "none";
        passwordField.disabled = true;
        document.getElementById("password-label").style.display = "none";

        button.innerText = "Request Access Link";

        forgotWrapper.style.display = "none";
        requestAccessWrapper.style.display = "none";

        document.querySelector(".continue").style.height = "330px";
    });
});