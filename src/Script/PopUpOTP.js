function showOTP(otp) {
    let popUpOTP = document.createElement("div");
    popUpOTP.classList.add("popUpOTP");
    popUpOTP.innerHTML = `
        <div class="popUpOTPContent">
            <h1>Code OTP :</h1>
            <p>Votre code OTP : ${otp}</p>
            <button onclick="closePopUpOTP()">Fermer</button>
        </div>
    `;
    document.body.appendChild(popUpOTP);
    document.body.classList.add("popUp-open");
}

function closePopUpOTP() {
    let popUpOTP = document.querySelector(".popUpOTP");
    if (popUpOTP) {
        popUpOTP.remove();
        document.body.classList.remove("popUp-open");
    }
}

window.onload = function() {
    let otp = "<?php echo $_SESSION['otp']; ?>";
    if (otp) {
        showOTP(otp);
        fetch('destroy_otp_session.php').then(function(response) {
            return response.text();
        }).then(function(data) {
            console.log(data);
        }).catch(function(err) {
            console.error(err);
        });
    }
}