/* UI handling for admin login & set credentials (NO AUTH HERE) */
document.addEventListener("DOMContentLoaded", () => {
    const forgotWrapper = document.getElementById("forgot-wrapper");
    forgotWrapper.style.display = "none";

    const form = document.getElementById("admin-form");
    const stepField = document.getElementById("step");

    const inputField = document.getElementById("email");
    const passwordField = document.getElementById("password");
    const button = document.getElementById("login-btn");
    const inputLabel = document.getElementById("input-label");
    const formTitle = document.getElementById("form-title");

    let step = "login";

    /* Show admin login */
    document.getElementById("show-admin").addEventListener("click", () => {
        document.getElementById("main-continue").style.display = "none";
        const adminBox = document.getElementById("admin-login");
        adminBox.style.display = "block";

        document.querySelector(".continue").style.height = "410px";
        adminBox.scrollIntoView({ behavior: "smooth" });

        step = "login";
        stepField.value = "login";

        forgotWrapper.style.display = "block";
    });

    /* Form submission (backend decides what happens) */
    form.addEventListener("submit", function () {
        stepField.value = step;
        // DO NOT preventDefault — let PHP handle auth
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

        document.querySelector(".continue").style.height = "440px";

        forgotWrapper.style.display = "none";
    };
});