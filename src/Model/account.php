<?php

namespace Account;

use Database\database;
use Exception;
use Users\users;

class account
{



    public function __construct(string $guid, string $password, string $salt)
    {

    }



    public static function getSalt($guid)
    {
        $db = new database();
        $db->testConnection();

        $stmt = $db->getConnection()->prepare ("SELECT salt FROM account WHERE guid = :guid");
        $stmt->bindValue(':guid', $guid);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result !== false) {
            return $result["salt"];
        } else {
            return null;
        }
    }



    public static function generateSalt(): string
    {
        return uniqid();
    }
    public static function hashedPassword(string $password) : string
    {
        $salt = self::generateSalt();
        return hash('sha512', $password . $salt);
    }





}