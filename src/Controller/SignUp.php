<?php

namespace SignUp;

use Account\Account;
use Users\Users;
use Log\Log;
use Exception;

class SignUp
{
    /**
     * @throws Exception
     */
    public static function execute(){
        // Vérification de la méthode de requête

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Récupération les données du formulaire

            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirmation = $_POST['password2'];

            // Verification que l'email soit bien un email et qu'il ne soit pas vide

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                new Log("L'email n'est pas valide");
                throw new Exception("L'email n'est pas valide");
            }

            // Vérification des mots de passe

            if ($password !== $passwordConfirmation) {

                new Log("Les mots de passe ne correspondent pas");
                throw new Exception("Les mots de passe ne correspondent pas");
            }

            // Création d'un objet Account

            $account = new Account('', $password, '');

            // Salage du mot de passe

            $result = $account->SaltPwd($password);
            $hashedPassword = $result['password'];
            $salt = $result['salt'];

            // Autres opérations avec le mot de passe hashé...

            echo "Youhou mot de passe salé : " . $hashedPassword;

            // Appel de la méthode createAccount pour créer le compte

            $account->createAccount($email, $hashedPassword, $salt);

            echo "Nouveaux enregistrements créés avec succès.";
        }
    }
}