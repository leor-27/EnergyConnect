/* for handling users and admin login and setting credentials */
document.addEventListener("DOMContentLoaded", () => {
    const ADMIN_EMAIL = "admin@example.com";
    const ADMIN_PASSWORD = "Admin123!";

    let step = "login";

    document.getElementById("show-admin").addEventListener("click", () => {
        document.getElementById("main-continue").style.display = "none";
        const adminBox = document.getElementById("admin-login");
        adminBox.style.display = "block";
        document.querySelector(".continue").style.height = "410px";
        adminBox.scrollIntoView({ behavior: "smooth" });
    });

    document.getElementById("admin-form").addEventListener("submit", function(e) {
        e.preventDefault();

        const inputField = document.getElementById("email");
        const passwordField = document.getElementById("password");
        const button = document.getElementById("login-btn");
        const inputLabel = document.getElementById("input-label");
        const formTitle = document.getElementById("form-title");
        const googleLogin = document.getElementById("google-login");

        const inputVal = inputField.value.trim();
        const passwordVal = passwordField.value.trim();

        if (!inputVal || !passwordVal) {
            alert("Please fill in both fields.");
            return;
        }

        if(step === "login") {

            if(inputVal === ADMIN_EMAIL && passwordVal === ADMIN_PASSWORD) {

                step = "set";
                formTitle.innerText = "Set Your Credentials";

                inputLabel.innerText = "Username";
                inputField.value = "";
                passwordField.value = "";
                button.innerText = "Save Credentials";

                // makes the continue box taller
                document.querySelector(".continue").style.height = "440px";

                googleLogin.style.display = "none";

                document.getElementById("name-label").style.display = "block";
                document.getElementById("name").style.display = "block";

            } else {
                alert("Incorrect email or password.");
            }

        } else if(step === "set") {

            const username = inputField.value.trim();
            const password = passwordField.value.trim();
            const nameVal = document.getElementById("name").value.trim();

            if(!username || !password || !nameVal) {
                alert("Please fill in all fields: Name, Username, and Password.");
                return;
            }

            alert(`Credentials Set:\nName: ${nameVal}\nUsername: ${username}`);
            window.location.href = "admin-home.html";
        }
    });
});