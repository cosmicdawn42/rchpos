// Live time function
function updateLiveTime() {
    const timeElement = document.getElementById('currentTime');
    const now = new Date();
    timeElement.textContent = now.toLocaleTimeString();
}

// Update every second
setInterval(updateLiveTime, 1000);

document.addEventListener("DOMContentLoaded", function () {
    const cancelButton = document.getElementById("cancel-btn");

    cancelButton.addEventListener("click", function () {
        // Clear all input fields
        document.getElementById("prod-name").value = '';
        document.getElementById("prod-price-hot").value = '';
        document.getElementById("prod-price-cold").value = '';
        document.getElementById("prod-qty").value = '';

        // Redirect to admin_dashboard.php
        window.location.href = "admin_dashboard.php";
    });
});
