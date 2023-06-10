<?php

namespace Users;


use Database\database;
use Exception;

class users
{
    private string $guid;
    private string $email;

    public function __construct(string $guid, string $email)
    {
    }

    public static function getGUID(): string
    {
        mt_srand((double)microtime() * 10000);
        $charid = strtoupper(uniqid(rand(), true));
        return substr($charid, 0, 8) . '-' .
            substr($charid, 8, 4) . '-' .
            substr($charid, 12, 4) . '-' .
            substr($charid, 16, 4) . '-' .
            substr($charid, 20, 12);
    }

    public static function GetAccountByMail(string $email): ?users
    {


        $query = "SELECT * FROM `users` WHERE `email` = ?";
        $statement = (new database())->getConnection()->prepare($query);

        if ($statement === false) {

            throw new Exception("Erreur database");
        }

        $statement->bindValue(1, $email);
        $statement->execute();
        $result = $statement->fetch();

        if ($result) {
            return new users($result['guid'], $result['email']);
        } else {

            return null;
        }
    }

    public static function CreateUser(string $guid, string $email)
    {
        // Création du compte


        $query = "INSERT INTO `users` (`guid`, `email`) VALUES (?, ?)";
        $statement = (new database())->getConnection()->prepare($query);

        if ($statement === false) {

            throw new Exception("Erreur database");
        }

        $statement->bindValue(1, $guid);
        $statement->bindValue(2, $email);
        $statement->execute();
    }

    public static function deleteUsers(string $guid): bool
    {
        $db = new database();
        $db->testConnection();

        $stmt = $db->getConnection()->prepare("DELETE FROM `users` WHERE `guid` = :guid");
        $stmt->bindValue(':guid', $guid);
        $stmt->execute();

        return $stmt->execute();
    }



    public static function getGUIDbyMail(string $email): string
    {
        $query = "SELECT `guid` FROM `users` WHERE `email` = ?";
        $statement = (new database())->getConnection()->prepare($query);
        $statement->bindValue(1, $email);
        $statement->execute();
        $result = $statement->fetch();
        return $result['guid'];
    }


    public static function getPassword(string $guid): ?string
    {
        $query = "SELECT password FROM account WHERE guid = :guid";
        $statement = (new database())->getConnection()->prepare($query);

        if ($statement === false) {
            throw new Exception("Erreur database");
        }

        $statement->bindParam(':guid', $guid);
        $statement->execute();
        $result = $statement->fetch();

        if ($result) {
            return $result['password'];
        } else {
            return null;
        }
    }


}