// Fonction affiche otp dans pop up

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