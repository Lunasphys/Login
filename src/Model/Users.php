<?php

namespace Users;

use Log\Log;
use Database\Database;
use Exception;

Class Users
{
    // Variable
    private string $GUID;
    private string $Email;

    // Constructor
    public function __construct(string $GUID, string $Email)
    {
        $this->GUID = $GUID;
        $this->Email = $Email;
        new Log ("Utilisateur créé");
    }

    // Main fonction

    /**
     * @throws Exception
     */
    public static function GetAccountById(): Users
    {
        new Log ("User::GetAccountById du compte [" . 'email' . "]");

        $query = "SELECT * FROM users WHERE GUID = :GUID";
        $statement = (new Database())->getConnection()->prepare($query);

        if ($statement === false) {
            new Log ("User::GetAccountById du compte [" . 'email'. "]");
            throw new Exception("Erreur Database");
        }

        $statement->bindParam(':GUID', $GUID);  // Corrigé ici
        $statement->execute();
        $result = $statement->fetch();

        if ($result) {
            return new Users($result['guid'], $result['email']);
        } else {
            throw new Exception("Error while fetching the result");
        }
    }

    /**
     * @throws Exception
     */
    public static function GetAccountByMail(string $Email): ?Users
    {
        new Log ("User::GetAccountByMail du compte [" . $Email . "]");

        $query = "SELECT * FROM users WHERE Email = :email";
        $statement = (new Database())->getConnection()->prepare($query);

        if ($statement === false) {
            new Log ("User::GetAccountByMail du compte [" . $Email . "]");
            throw new Exception("Erreur Database");
        }

        $statement->bindParam(':Email', $Email);
        $statement->execute();
        $result = $statement->fetch();

        if ($result) {
            return new Users($result['Email'], $result['GUID']);
        } else {
            new Log ("Pas de compte existant avec [" . $Email . "]");
            return null;
        }
    }

    public static function CreateUser(string $GUID, string $Email)
    {
        new Log ("User::CreateUser du compte [" . $Email . "]");
        $query = "INSERT INTO users (GUID, Email) VALUES (:GUID, :Email)";
        $statement = (new Database())->getConnection()->prepare($query);

        if ($statement === false) {
            new Log ("User::CreateUser du compte [" . $Email . "]");
            throw new Exception("Erreur Database");
        }

        $statement->bindParam(':GUID', $GUID);
        $statement->bindParam(':Email', $Email);
        $statement->execute();
    }
}