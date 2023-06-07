<?php

namespace Users;

use Log\log;
use Database\database;
use Exception;

Class users
{
    // Variable
    private string $guid;
    private string $email;

    // Constructor
    public function __construct(string $guid, string $email)
    {
        $this->guid = $guid;
        $this->email = $email;
        new log ("Utilisateur créé");
    }

    /**
     * @throws Exception
     */
    public static function GetAccountById(string $guid): users
    {
        // Récupère le guid et le met dans account

        new log ("User::GetAccountById du compte [" . $guid . "]");

        $query = "SELECT * FROM users WHERE guid = :guid";
        $statement = (new database())->getConnection()->prepare($query);

        if ($statement === false) {
            new log ("user::GetAccountById du compte [" . $guid . "]");
            throw new Exception("Erreur database");
        }

        $statement->bindParam(':guid', $guid);
        $statement->execute();
        $result = $statement->fetch();

        if ($result) {
            return new users($result['guid'], $result['email']);
        } else {
            throw new Exception("erreur de fetch");
        }
    }

    /**
     * @throws Exception
     */
    public static function GetAccountByMail(string $email): ?users
    {

        // Permet de récupérer le compte par son email

        new log ("User::GetAccountByMail du compte [" . $email . "]");

        $query = "SELECT * FROM users WHERE email = :email";
        $statement = (new database())->getConnection()->prepare($query);

        if ($statement === false) {
            new log ("users::GetAccountByMail du compte [" . $email . "]");
            throw new Exception("Erreur database");
        }

        $statement->bindParam(':email', $email);
        $statement->execute();
        $result = $statement->fetch();

        // Si le compte existe, on le retourne, sinon on retourne null

        if ($result) {
            return new users($result['email'], $result['guid']);
        } else {
            new log ("Pas de compte existant avec [" . $email . "]");
            return null;
        }
    }

    public static function CreateUser(string $guid, string $email)
    {
        // Permet de créer une table users

        new log ("User::CreateUser du compte [" . $email . "]");
        $query = "INSERT INTO users (guid, email) VALUES (:guid, :email)";
        $statement = (new database())->getConnection()->prepare($query);


        // Si la requête ne fonctionne pas, on retourne une erreur

        if ($statement === false) {
            new log ("User::CreateUser du compte [" . $email . "]");
            throw new Exception("Erreur database");
        }

        $statement->bindParam(':guid', $guid);
        $statement->bindParam(':email', $email);
        $statement->execute();
    }

    public static function deleteUser(string $guid) {
        $query = "DELETE FROM users WHERE guid = :guid";
        $statement = (new database())->getConnection()->prepare($query);
        $statement->bindParam(':guid', $guid);
        $statement->execute();
    }
}