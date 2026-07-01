// Archivo: assets/js/main.js

document.addEventListener("DOMContentLoaded", () => {
    const registroForm = document.getElementById("registroForm");

    if (registroForm) {
        registroForm.addEventListener("submit", function(event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            // Validación del lado cliente para evitar peticiones innecesarias
            if (password.length < 8) {
                event.preventDefault();
                alert("La contraseña debe tener al menos 8 caracteres.");
                return;
            }

            if (password !== confirmPassword) {
                event.preventDefault();
                alert("Las contraseñas no coinciden. Por favor, verifíquelas.");
            }
        });
    }
});