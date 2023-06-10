<?php

namespace Account_attempts;

use Database\database;
use Exception;
use Users\users;
use Account\account;


class account_attempts
{


    public function __construct(int $guid, int $attempt_at)
    {
    }

    public static function CreateAccountAttempts(string $guid, string $attempt_at): ?account_attempts
    {

        $db = new database();
        $db->testConnection();

        $query = "INSERT INTO accountattempts (guid, attempt_at) VALUES (:guid, :attempt_at)";
        $statement = $db->getConnection()->prepare($query);
        $statement->execute([
            'guid' => $guid,
            'attempt_at' => $attempt_at
        ]);

        $result = $statement->fetch();

        if ($result) {
            return new account_attempts($result["guid"], $result["attempt_at"]);
        } else {
            return null;
        }
    }

    public static function lastAttempts(string $guid): ?string
    {
        $db = new database();
        $db->testConnection();

        $query = "SELECT attempt_at FROM accountattempts WHERE guid = :guid ORDER BY attempt_at DESC LIMIT 1";
        $statement = $db->getConnection()->prepare($query);
        $statement->execute([
            'guid' => $guid
        ]);

        $result = $statement->fetch();
         if ($result) {
            return $result['attempt_at'];
        } else {
            return null;
         }
    }

    public static function canConnect(string $guid) : bool
    {
        $db = new database();
        $db->testConnection();


        $lastAttempt = strtotime(self::lastAttempts($guid));
         $now = time();
         $diff = $now - $lastAttempt;
         if ($diff > 60) {
             self::deleteAttempts($guid);
            return true;
         } else {
             return false;
            }
        }


        public static function deleteAttempts(string $guid)
        {
            $db = new database();
            $db->testConnection();

            $query = "DELETE FROM accountattempts WHERE guid = :guid";
            $statement = $db->getConnection()->prepare($query);
            $statement->execute([
                'guid' => $guid
            ]);
        }

}