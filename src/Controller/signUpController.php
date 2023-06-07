<?php

namespace SignUp;

use Account\account;
use Users\users;
use Log\log;
use Exception;
use Account_otp\account_otp;

class signUpController
{
    /**
     * @throws Exception
     */
    public static function execute()
    {


        // Récupération les données du formulaire

        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password2'];

        // Verification que l'email soit bien un email et qu'il ne soit pas vide

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            new log("L'email n'est pas valide");
            throw new Exception("L'email n'est pas valide");
        }

        // Vérification des mots de passe

        if ($password !== $passwordConfirmation) {

            new log("Les mots de passe ne correspondent pas");
            throw new Exception("Les mots de passe ne correspondent pas");
        }

        // Récupération de la ligne gud

        $guid = account::getGUID();


        $response = null;

        // Salage du mot de passe

        $result = account::SaltPwd($password);
        $hashedPassword = $result['password'];
        $salt = $result['salt'];


        echo "Youhou mot de passe salé : " . $hashedPassword;

        // Appel de la méthode createAccount pour créer l'account temporaire


        $success = account::createAccount();

        if ($success) {
            $otp = account_otp::getOTP($guid);

            $_SESSION['otp'] = $otp; // Save OTP to a session variable

            account::confirmOTP($guid, $otp);

            // Redirection to the login page with the OTP in the URL

            echo "reussi";
            exit();

        }
        else
        {
            echo "La création du compte a échoué.";
        }
    }
}