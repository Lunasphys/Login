<?php

namespace SignUp;

use Account\account;
use Users\users;
use Log\log;
use Exception;
use Account_otp\account_otp;

class signUp
{
    /**
     * @throws Exception
     */
    public static function execute()
    {
        // Vérification de la méthode de requête

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

            $account = new account($guid, $password, '');

            // Salage du mot de passe

            $result = $account->SaltPwd($password);
            $hashedPassword = $result['password'];
            $salt = $result['salt'];

            echo "Youhou mot de passe salé : " . $hashedPassword;

            // Appel de la méthode createAccount pour créer l'account temporaire


            $success = $account->createAccount();

            if ($success) {
                // Génération de l'OTP

                $otp = account_otp::generateOTP(8);

                $validity = date('Y-m-d H:i:s', strtotime('+1 MINUTE'));

                // Enregistre l'OTP dans la table accountotp puis vérifie sa confirmation

                account_otp::createOTP($guid, $otp, $validity);

                // Affichage de la page de succès avec la pop-up OTP

                echo "<script>showOTP('" . $otp . "');</script>";

                // Appeler la méthode confirmOTP()

                account::confirmOTP($guid, $otp);

                // Redirection vers la page de connexion avec l'OTP dans l'URL

                header('Location: connexion.html?otp=' . urlencode($otp));
                exit();

            }
            else
            {
                echo "La création du compte a échoué.";
            }
        }
    }
}