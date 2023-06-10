<?php

namespace OtpVerificationController;

use Account_otp\account_otp;
use Account_tmp\account_tmp;
use Users\users;



class otpVerificationController
{
    /**
     * @throws \Exception
     */
    public static function execute()
    {
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $errorMessage = "";
            $successMessage = "";
            $submitted_otp = $_POST['otpInput'];

            if (isset($_COOKIE['guid']) && isset($_COOKIE['otp'])) {
                $guid = $_COOKIE['guid'];
                $stored_otp = $_COOKIE['otp'];

                if (account_otp::getOTPSecondsRemaining($guid) === 0 && $submitted_otp || account_otp::getOTPSecondsRemaining($guid) === null && $submitted_otp) {

                    echo "Le temps de validation de l'OTP est dépassé. Veuillez recommencer.";
                    echo "<a href='../../Template/signUp.php'>Retour à la page d'inscription</a>";
                    users::deleteUsers($guid);

                    header('Location: ../../Template/signUp.php');



                }
                elseif ($submitted_otp === $stored_otp)
                {
                    setcookie("otp_verification_result", "success", time() + 60);
                    account_tmp::accountTMPtoAccount($guid);
                    account_tmp::deleteAccountTmp($guid);
                    account_otp::deleteOTP($guid);
                    header('Location: isConnected.php');
                    exit();
                }
                else
                {
                    echo "<p> Mauvais OTP </p>";
                    echo "<a href='../../Template/otpVerification.php'>Retour à la page pour rentrer l'OTP</a>";

                    $errorMessage = "L'OTP saisi est incorrect. Veuillez réessayer.";
                }
            } else {
                $errorMessage = "Erreur lors de la vérification de l'OTP. Veuillez réessayer.";
            }
        }
        exit();
    }
}