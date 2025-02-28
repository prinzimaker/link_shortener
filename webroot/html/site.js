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
