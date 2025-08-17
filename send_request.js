function showMessage(type, text) {
    let box = document.getElementById("formMessage");
    let icon = document.getElementById("messageIcon");
    let msgText = document.getElementById("messageText");

    // Reset classes
    box.className = "message-box";
    
    if (type === "success") {
        box.classList.add("success");
        icon.className = "fas fa-check-circle";
    } else if (type === "error") {
        box.classList.add("error");
        icon.className = "fas fa-exclamation-circle";
    }

    msgText.textContent = text;
    box.classList.remove("hidden");

    // Auto-hide after 4 seconds
    setTimeout(() => {
        box.classList.add("hidden");
    }, 4000);
}



document.getElementById("requestForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Stop page reload

    // Collect form values
    let requestData = {
        produce: document.getElementById("produceType").value,
        quantity: document.getElementById("quantityDisplay").innerText,
        unit: document.querySelector(".unit-btn.active").innerText,
        pickup: document.getElementById("pickupLocation").value,
        destination: document.getElementById("destination").value,
        urgency: document.getElementById("urgency").value,
        additional: document.getElementById("additionalInfo").value
    };

    // Send to PHP
    fetch("send_request.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(requestData)
})
.then(res => res.text()) // get raw text
.then(text => {
    console.log("RAW RESPONSE:", text); // see what's coming from PHP
    return JSON.parse(text); // manually parse if it’s valid
})
.then(data => {
    if (data.status === "success") {
        showMessage("success", "Request sent successfully");
    } else {
        showMessage("error", data.message);
    }
    console.log(data);
})
.catch(err => {
    console.error("Parse error:", err);
    showMessage("error", "An error occurred. Please try again.");
});


});

