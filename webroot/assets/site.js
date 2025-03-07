/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Written By Aldo Prinzi 2024/2025 
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains some javascript generally needed
-
v1.4.1 - Aldo Prinzi - 07 Mar 2025
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

function evaluatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8)   strength++;
    if (password.length >= 12)  strength++;
    if (/\d/.test(password))    strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[\W_]/.test(password)) strength++;
    return strength;
}

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

function copyData(elem,field,cpLnk,cpErr){
    var copyText="";
    switch(field){
        case 'itext':
            copyText=document.getElementById(elem).innerText;
            break;
        case 'src':
            copyText=document.getElementById(elem).src;
            break;
        default:
            copyText=document.getElementById(elem).value;
            break;
    }
    if (copyText){
        navigator.clipboard.writeText(copyText).then(
            function(){alert(cpLnk+": "+ copyText);},
            function(err){console.error(err+"\n"+cpErr);}
        );
    }
}