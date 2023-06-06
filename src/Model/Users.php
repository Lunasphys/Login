<?php

namespace Users;

use Log\Log;
use Database\Database;
use Exception;

Class Users
{
    // Variable
    private string $guid;
    private string $email;

    // Constructor
    public function __construct(string $guid, string $email)
    {
        $this->guid = $guid;
        $this->email = $email;
        new Log ("Utilisateur créé");
    }

    // Main fonction

    /**
     * @throws Exception
     */
    public static function GetAccountById(): Users
    {
        new Log ("User::GetAccountById du compte [" . 'email' . "]");

        $query = "SELECT * FROM users WHERE guid = :guid";
        $statement = (new Database())->getConnection()->prepare($query);

        if ($statement === false) {
            new Log ("user::GetAccountById du compte [" . 'email'. "]");
            throw new Exception("Erreur Database");
        }

        $statement->bindParam(':guid', $guid);  // Corrigé ici
        $statement->execute();
        $result = $statement->fetch();

        if ($result) {
            return new Users($result['guid'], $result['email']);
        } else {
            throw new Exception("erreur de fetch");
        }
    }

    /**
     * @throws Exception
     */
    public static function GetAccountByMail(string $email): ?users
    {
        new Log ("User::GetAccountByMail du compte [" . $email . "]");

        $query = "SELECT * FROM users WHERE email = :email";
        $statement = (new Database())->getConnection()->prepare($query);

        if ($statement === false) {
            new Log ("Users::GetAccountByMail du compte [" . $email . "]");
            throw new Exception("Erreur Database");
        }

        $statement->bindParam(':email', $Email);
        $statement->execute();
        $result = $statement->fetch();

        if ($result) {
            return new Users($result['email'], $result['guid']);
        } else {
            new Log ("Pas de compte existant avec [" . $email . "]");
            return null;
        }
    }

    public static function CreateUser(string $guid, string $email)
    {
        new Log ("User::CreateUser du compte [" . $email . "]");
        $query = "INSERT INTO users (guid, email) VALUES (:guid, :email)";
        $statement = (new Database())->getConnection()->prepare($query);

        if ($statement === false) {
            new Log ("User::CreateUser du compte [" . $email . "]");
            throw new Exception("Erreur Database");
        }

        $statement->bindParam(':guid', $guid);
        $statement->bindParam(':email', $email);
        $statement->execute();
    }
}