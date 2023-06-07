<?php
namespace Account_tmp;

use Database\database;
use Log\log;


class account_tmp
{
    private string $guid;
    private string $password;
    private string $salt;

    public static function CreateAccountTmp(string $guid, string $password, string $salt) {
        /*$query = "INSERT INTO accounttmp (guid, password, salt) VALUES (:guid, :password, :salt)";
        $statement = (new database())->getConnection()->prepare($query);

        if ($statement === false) {
            throw new Exception("Erreur database");
        }

        $statement->bindParam(':guid', $guid);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':salt', $salt);
        $statement->execute();*/
    }

    // Supprime la ligne dans la table accounttmp
    public static function deleteAccountTmp(string $guid)
    {
        $query = "DELETE FROM accounttmp WHERE guid = :guid";
        $statement = (new database())->getConnection()->prepare($query);
        $statement->bindParam(':guid', $guid);
        $statement->execute();
    }
}