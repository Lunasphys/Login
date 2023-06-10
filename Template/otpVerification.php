
<?php

use OtpVerificationController\otpVerificationController;
use Account_otp\account_otp;


require_once '../src/Pdo/database.php';
require_once '../src/Model/account.php';
require_once '../src/Model/account_otp.php';
require_once '../src/Model/account_tmp.php';
require_once '../src/Model/users.php';
require_once '../src/Controller/otpVerificationController.php';

ob_start();

if (isset($_COOKIE['test_cookie'])) {
    echo "Les cookies sont activés. ton otp s'y trouve";
} else {
    echo "Les cookies sont désactivés.";
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    try {
        otpVerificationController::execute();

        if ($_COOKIE['otp_verification_result'] === "success") {
            echo "OTP verified";
        } else {
            echo "OTP verification failed";
        }

        // Supprime le cookie
        setcookie("otp_verification_result", "", time() - 60);
    } catch (\Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}

$secondsRemaining = "";
if (isset($_COOKIE['guid'])) {
    $guid = $_COOKIE['guid'];
    $secondsRemaining = account_otp::getOTPSecondsRemaining($guid);
}
else {
    $secondsRemaining = 0;
    echo "Le temps de validation de l'OTP est dépassé. Veuillez recommencer.";
    echo "<a href='Template/signUp.php'>Retour à la page d'inscription</a>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body>
<h1>OTP Verification</h1>

<p>Temps restant : <?php echo $secondsRemaining; ?> secondes</p>

<p>Entrez l'OTP reçu</p>
<form action="/Template/otpVerification.php" method="POST">
    <label>
        <input type="text" name="otpInput" placeholder="Entrez l'OTP">
    </label>
    <button type="submit" value="OTPSubmit">Soumettre</button>
</form>


</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
