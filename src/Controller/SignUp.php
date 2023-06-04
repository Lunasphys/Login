<?php

namespace SignUp;

use Account\Account;
use Log\Log;
use Exception;

class SignUp
{
    /**
     * @throws Exception
     */
    public static function execute() {
        // Mise en place du mdp (hashé et salé) et de l'adresse mail (verification avec bdd)
        new Log ("Essai d'enregistrement");
        $password = $_POST['password'];
        $salt = bin2hex(random_bytes(32));
        $hash_password = hash('sha512', $password . $salt);
        $email = $_POST['email'];

        if ($password != $_POST['password2']) {
            new Log ("Les mots de passe ne correspondent pas");
            throw new Exception("Les mots de passe ne correspondent pas");
        } else {}
    }
}