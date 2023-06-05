<?php
namespace Account;

use Database\Database;
use Exception;
use Log\Log;
use Users\Users;
use function Sodium\compare;


class Account {
    // Variable
    private string $GUID;
    private string $Password;
    private string $Salt;

    // Constructor

    public function __construct(string $GUID, string $Password, string $Salt) {
        $this->GUID = $GUID;
        $this->Password = $Password;
        $this->Salt = $Salt;
        new Log ("Compte créé");
    }

    // Main fonction
    //Récupère le guid et le met dans account

    public function getGUID(): string {
        return $this->GUID;
    }
    public  static function SaltPwd(string $Password): array {
        // Génère un sel aléatoire
        $salt = random_bytes(32);
        // Hash le mot de passe avec le sel
        $hashedPassword = hash('sha512', $Password . $salt);
        return ['password' => $hashedPassword, 'salt' => $salt];
    }

    public static function createAccount(string $GUID, string $Password, string $Salt) {

            $db = new Database();
            $db->testConnection();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $Email = $_POST['email'];
                $Password = $_POST['password'];

                // Vérifie si l'email existe déjà dans la base de données

                $existingUser = Users::GetAccountByMail($Email);
                if ($existingUser) {
                    echo "Cet email est déjà utilisé.";
                    return;
                }

                // Génère le GUID unique

                $generatedGUID = uniqid();

                // Crée un objet Account

                $account = new Account($generatedGUID, $Password, '');

                // Sale le mot de passe

                $result = $account->SaltPwd($Password);
                $hashedPassword = $result['password'];
                $salt = $result['salt'];

                // Autres opérations avec le mot de passe hashé...

                echo "Youhou mot de passe salé : " . $hashedPassword;

                // Coconnexion bdd
                try {
                    $link = $db->getConnection();
                } catch (Exception $e) {
                }


                // Insère l'email dans la table Users en utilisant la méthode CreateUser

                Users::CreateUser($generatedGUID, $Email);


                // Insère les autres données dans la table account

                $stmt1 = $link->prepare("INSERT INTO account (GUID, Password, Salt) VALUES (?, ?, ?)");
                $stmt1->bindParam(1, $generatedGUID);
                $stmt1->bindParam(2, $hashedPassword);
                $stmt1->bindParam(3, $salt);
                $stmt1->execute();
                $stmt1->closeCursor();

                // Ferme la connexion

                $link = null;

                echo "Nouveaux enregistrements créés avec succès.";
        }


    }

    public static function comparePassword(string $password, string $hashedPassword, string $salt): bool
    {
        // Compare le mot de passe entré avec le mot de passe hashé + salé

        $saltedPassword = hash('sha512', $password . $salt);

        if (hash_equals($hashedPassword, $saltedPassword)) {
            return true;
        } else {
            echo "Mot de passe incorrect";
            return false;

        }
    }

    public static function connexion(string $email, string $password, string $hashedPassword, string $salt): ?Users {
        new Log("Connexion de l'utilisateur" . $email);

        // Récupère le compte en fonction de l'email

        $account = Users::GetAccountByMail($email);


        // Va comparer les mots de passe et retourner la page connectée si l'association est bonne

        if (!Account::comparePassword($password, $hashedPassword, $salt)) {
            return null;
        } else {
            return $account;
        }
    }
}