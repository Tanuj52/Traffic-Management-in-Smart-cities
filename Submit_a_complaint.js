document.addEventListener("DOMContentLoaded", function() {
    const complaintForm = document.getElementById("complaint-form");
    const dialogContainer = document.getElementById("dialog-container");
    const dialogIcon = document.getElementById("dialog-icon");
    const dialogMessage = document.getElementById("dialog-message");
    const dialogOK = document.getElementById("dialog-ok");
    const backButton = document.querySelector(".back-button");

    complaintForm.addEventListener("submit", function(event) {
        event.preventDefault();

        fetch(this.action, {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDialog("tick", "Your complaint has been submitted successfully.");
            } else {
                showDialog("cross", "Error submitting complaint. Please try again later.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showDialog("cross", "Error submitting complaint. Please try again later.");
        });
    });

    dialogOK.addEventListener("click", function(event) {
        event.preventDefault();
        hideDialog();
    });

    backButton.addEventListener("click", function(event) {
        event.preventDefault();
        window.history.back();
    });

    function showDialog(iconType, message) {
        dialogIcon.innerHTML = getIconSVG(iconType);
        dialogMessage.textContent = message;
        dialogContainer.classList.remove("hidden");
    }

    function hideDialog() {
        dialogContainer.classList.add("hidden");
    }

    function getIconSVG(type) {
        if (type === "tick") {
            return '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#4CAF50"/><path fill="#FFF" d="M9 16.17L5.83 13l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>';
        } else if (type === "cross") {
            return '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#F44336"/><path fill="#FFF" d="M7.41 7.41L12 12l4.59-4.59L18 9l-4.59 4.59L12 15l-4.59-4.59L6 9z"/></svg>';
        } else {
            return '';
        }
    }
});
