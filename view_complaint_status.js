document.addEventListener("DOMContentLoaded", function() {
    const statusForm = document.getElementById("status-form");
    const complaintDetails = document.getElementById("complaint-details");
    const backButton = document.querySelector(".back-button");
    const fullname = document.getElementById("fullname");
    const email = document.getElementById("email");
    const complaint = document.getElementById("complaint");
    const submittedAt = document.getElementById("submitted_at");

    statusForm.addEventListener("submit", function(event) {
        event.preventDefault();

        fetch(this.action, {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fullname.textContent = data.complaint_details.fullname;
                email.textContent = data.complaint_details.email;
                complaint.textContent = data.complaint_details.complaint;
                submittedAt.textContent = data.complaint_details.submitted_at;
                complaintDetails.classList.remove("hidden");
            } else {
                alert(data.message);
                complaintDetails.classList.add("hidden");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error retrieving complaint status. Please try again later.");
            complaintDetails.classList.add("hidden");
        });
    });

    backButton.addEventListener("click", function(event) {
        event.preventDefault();
        window.history.back();
    });
});
