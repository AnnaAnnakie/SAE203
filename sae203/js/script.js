document.addEventListener("DOMContentLoaded", () => {
    const togglers = document.querySelectorAll(".toggle-password");

    togglers.forEach(toggler => {
        toggler.addEventListener("click", function() {
            const passwordInput = this.parentElement.querySelector("input");
            const eyeOpen = this.querySelector(".icon-eye-open");
            const eyeClose = this.querySelector(".icon-eye-close");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeOpen.classList.add("hidden");
                eyeClose.classList.remove("hidden");
            } else {
                passwordInput.type = "password";
                eyeOpen.classList.remove("hidden");
                eyeClose.classList.add("hidden");
            }
        });
    });

    const signUpForm = document.getElementById("signUpForm");

    if (signUpForm) {
        signUpForm.addEventListener("submit", function(event) {
            const usernameInput = document.getElementById("newUsername");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");

            const oldAlert = signUpForm.querySelector(".js-error-alert");
            if (oldAlert) oldAlert.remove();

            const usernameRegex = /^[a-zA-Z0-9_ \-]+$/;
            const passwordRegex = /^[^'"\\\s]+$/;

            let errorMessage = "";

            if (!usernameRegex.test(usernameInput.value)) {
                errorMessage = "Le nom d'utilisateur ne doit contenir que des lettres, chiffres, tirets ou espaces.";
                usernameInput.focus();
            } else if (!passwordRegex.test(passwordInput.value)) {
                errorMessage = "Le mot de passe ne doit pas contenir d'espaces, de guillemets (', \") ou d'antislashs (\\).";
                passwordInput.focus();
            }

            if (errorMessage !== "") {
                event.preventDefault();

                // Création d'un paragraphe d'erreur identique à tes styles PHP
                const errorParagraph = document.createElement("p");
                errorParagraph.className = "error js-error-alert";
                errorParagraph.textContent = errorMessage;

                // Insertion de l'erreur juste avant le bouton de soumission
                const submitBtn = signUpForm.querySelector("button[type='submit']");
                signUpForm.insertBefore(errorParagraph, submitBtn);
            }
        });
    }
});