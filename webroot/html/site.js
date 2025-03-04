/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Written By Aldo Prinzi 2024/2025 
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains some javascript generally needed
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025
=====================================================================
*/
document.addEventListener('DOMContentLoaded', function() {
    let modalBackdrop = document.getElementById('modalBk');
    if (!modalBackdrop) {
        modalBackdrop = document.createElement('div');
        modalBackdrop.id = 'modalBk';
        modalBackdrop.className = 'modalBackdrop hidden';
        document.body.appendChild(modalBackdrop);
    }
});

function openmodal(){
    document.getElementById("modalBk").classList.remove("hidden");
    document.getElementById("modal").classList.remove("hidden");
}
function closemodal(){
    document.getElementById("modalBk").classList.add("hidden");
    document.getElementById("modal").classList.add("hidden");
}

// Valuta la sicurezza della password e restituisce un punteggio da 0 a 5
function evaluatePasswordStrength(password) {
    let strength = 0;
    // Controllo lunghezza
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    // Se contiene numeri
    if (/\d/.test(password)) strength++;
    // Se contiene sia minuscole che maiuscole
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    // Se contiene caratteri speciali
    if (/[\W_]/.test(password)) strength++;
    return strength;
}

// Aggiorna il display del livello di sicurezza della password
function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strength = evaluatePasswordStrength(password);
    let strengthText = "";
    switch (strength) {
        case 0:
        case 1:
            strengthText = "Molto debole";
            break;
        case 2:
            strengthText = "Debole";
            break;
        case 3:
            strengthText = "Media";
            break;
        case 4:
            strengthText = "Forte";
            break;
        case 5:
            strengthText = "Molto forte";
            break;
        default:
            strengthText = "";
    }
    document.getElementById('passwordStrength').innerText = "Sicurezza: " + strengthText;
}

