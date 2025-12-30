document.addEventListener("DOMContentLoaded", () => {
    const forgotWrapper = document.getElementById("forgot-wrapper");
    const form = document.getElementById("admin-form");
    const stepField = document.getElementById("step");
    const inputField = document.getElementById("email");
    const passwordField = document.getElementById("password");
    const button = document.getElementById("login-btn");
    const inputLabel = document.getElementById("input-label");
    const formTitle = document.getElementById("form-title");

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

        forgotWrapper.style.display = "block";
    });

    /* Form submission (AJAX) */
    form.addEventListener("submit", function (e) {
        e.preventDefault(); // prevent normal form submission
        stepField.value = step;

        const formData = new FormData(form);

        fetch("backend/admin-auth.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            if (response === "success") {
                window.location.href = "admin-home.php"; // redirect on success
            } else if (response === "setup") {
                showSetCredentials(); // first-time login UI
            } else {
                alert(response); // show error messages
            }
        })
        .catch(err => console.error(err));
    });

    /* This function is called AFTER backend confirms temp login */
    window.showSetCredentials = function () {
        step = "set";
        stepField.value = "set";

        formTitle.innerText = "Set Your Credentials";
        inputLabel.innerText = "Username";

        inputField.value = "";
        passwordField.value = "";
        button.innerText = "Save Credentials";

        document.querySelector(".continue").style.height = "400px";

        forgotWrapper.style.display = "none";
    };
});