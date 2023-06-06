<?php

namespace Login;

use Database\database;
use Exception;
use Users\users;
use Account\account;
use Log\log;

class login
{
    /**
     * @throws Exception
     */
    public static function execute()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Récupération les données du formulaire

            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashedPassword = account::SaltPwd($password)['password'];
            $salt = account::SaltPwd($password)['salt'];

            // Verification que l'email soit bien un email et qu'il ne soit pas vide

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                new log("L'email n'est pas valide");
                throw new Exception("L'email n'est pas valide, veuillez rentrer un vrai email");
            }


            $acc = account::connexion($email, $password, $hashedPassword, $salt);

            if ($acc !== null) {
                echo "Vous êtes connecté";
                $_SESSION['guid'] = $acc->GetAccountById();
                header('Location: /');
            } else {
                echo "Vous n'êtes pas connecté, email et mdp ne matchent pas";
            }

        }
    }
}
