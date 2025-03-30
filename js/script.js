// filepath: dynamic-webpage-project/dynamic-webpage-project/js/script.js
// This file contains JavaScript code to control the logo's rotation, fade-out effect, and redirection to homepage.php after the animation ends.

document.addEventListener("DOMContentLoaded", function() {
    const logo = document.getElementById("logo");
    const myobject = document.getElementById("myobject");

    // Start the animation
    logo.classList.add("rotate");

    // After 3 seconds, fade out the logo and redirect
    setTimeout(() => {
        logo.classList.add("fade-out");
        myobject.classList.add("fade-out");
        setTimeout(() => {
            window.location.href = "../homepage.php"; // Redirect to homepage.php
        }, 1000); // Wait for fade-out duration before redirecting
    }, 3000); // Duration of rotation
});