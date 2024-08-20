// main.js
function navigate() {
    var select = document.getElementById("nav");
    var value = select.value;
    if (value) {
        window.location.href = value; // Redirect to selected page
    }
}
