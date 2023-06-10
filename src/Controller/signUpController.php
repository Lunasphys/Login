<?php

namespace SignUpController;


use Account\account;
use Account_otp\account_otp;
use Account_tmp\account_tmp;
use Users\users;


class signUpController
{
    /**
     * @throws \Exception
     */
    public static function execute()
    {
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];



            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return "Le format de l'e-mail est incorrect";
            }



            // Verifie si suit les conditions de mot de passe

            if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
                // Le mot de passe est valide
                echo "Mot de passe valide.";
            } else {

                // Mot de passe non coinforme

                return "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.";
            }


            if ($password !== $password2) {
                return "Les mots de passe ne correspondent pas";
            }
            $user = users::GetAccountByMail($email);
            if ($user !== null) {
                return "Un compte existe déjà avec cet email";
            } else {
                $salt = account::generateSalt();
                $saltPassword = $password . $salt;
                $hashedPassword = hash('sha512', $saltPassword);
                $guid = users::getGUID();
                users::CreateUser($guid, $email);
                account_tmp::CreateAccountTmp($guid, $hashedPassword, $salt);

                echo "Tmp créé";
                $validity = date('Y-m-d H:i:s', strtotime('+1 minute'));
                $otp = account_otp::generateOTP(6);
                account_otp::createOTP($guid, $otp, $validity);
                setcookie("otp", $otp, time() + 60); // 60 secondes = 1 minute
                setcookie("guid", $guid, time() + 3600);
                header('Location: ../../Template/otpVerification.php');
                exit();
            }
        }
        exit();
    }
}