const deliveredBtn = document.querySelector('#deliveredBtn');

deliveredBtn.addEventListener('click', () => {
    fetch('deliverStatus.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },

        body: ""

    })
})