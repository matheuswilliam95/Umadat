document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("menu");
    menuToggle.addEventListener("click", function() {
        menu.style.display = menu.style.display === "flex" ? "none" : "flex";
    });
});