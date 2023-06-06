<?php
namespace Account_tmp;

use Database\database;
use Log\log;


class account_tmp
{
    private string $guid;
    private string $password;
    private string $salt;

    public static function createAccountTmp(string $guid, string $password, string $salt) {

        // Connexion db

        $db = new database();
        $db->testConnection();

        // Insertion des données dans la table accounttmp

        new log ("Le compte va se créer dans la table accounttmp");

        $stmt = $db->getConnection()->prepare("INSERT INTO `accounttmp` (guid, password, salt) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $guid);
        $stmt->bindValue(2, $password);
        $stmt->bindValue(3, $salt);
        $stmt->execute();

        echo "Compte créé dans tmp";

    }
}