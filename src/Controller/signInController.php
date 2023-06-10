<?php

namespace SignInController;

use Account\account;
use Account_attempts\account_attempts;
use Users\users;

class signInController
{
    /**
     * @throws \Exception
     */
    public static function execute()
    {
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];


            // Vérifier les informations d'identification de l'utilisateur
            $user = users::GetAccountByMail($email);

            if ($user) {
                $guid = users::getGUIDbyMail($email);

                $salt = account::getSalt($guid);

                $saltedPassword = $password . $salt;


                $hashedPassword = hash('sha512', $saltedPassword);

                $storedPassword = $user->getPassword($guid);

                if (account_attempts::canConnect($guid)) {
                    if (hash_equals($hashedPassword, $storedPassword)) {
                        setcookie('logged_in', 'true', time() + 3600, '/');
                        // Le mot de passe est correct, l'utilisateur est connecté
                        header('Location: ../../Template/isConnected.php');
                        exit();
                    } else {
                        // Le mot de passe n'est pas valide
                        $attempt_at = date("Y-m-d H:i:s");
                        account_attempts::CreateAccountAttempts($guid, $attempt_at);
                        return "Le mot de passe est incorrect";
                    }
                } else {
                    return "Trop de tentatives de connexion, veuillez réessayer plus tard(1minute)";
                }
            }

            return "L'utilisateur n'existe pas";
        }
    }
}