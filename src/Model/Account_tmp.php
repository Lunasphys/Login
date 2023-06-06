<?php
namespace Account_tmp;

use Database\Database;
use Exception;
use Log\Log;
use Users\Users;
use Account_otp\Account_otp;

class Account_tmp
{
    private string $guid;
    private string $pwd;
    private string $salt;

    public static function createAccountTmp(string $guid, string $pwd, string $salt) {

        // Connexion db

        $db = new Database();
        $db->testConnection();

        // Insertion des données dans la table accounttmp

        new Log ("Le compte va se créer dans la table accounttmp");

        $stmt = $db->getConnection()->prepare("INSERT INTO `accounttmp` (`guid`, `pwd`, `salt`) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $guid);
        $stmt->bindValue(2, $pwd);
        $stmt->bindValue(3, $salt);
        $stmt->execute();

        echo "Compte créé dans tmp";

    }
}