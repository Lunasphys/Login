<?php
namespace Account;

use Database\Database;
use Exception;
use Log\Log;
use Users\Users;
use Account_otp\Account_otp;


class Account {
    // Variable
    private string $guid;
    private string $password;
    private string $salt;

    // Constructor

    public function __construct(string $guid, string $password, string $salt) {
        $this->guid = $guid;
        $this->password = $password;
        $this->salt = $salt;
        new Log ("Compte créé");
    }

    //Récupère le guid et le met dans account

    public function getGUID(): string {
        return $this->guid;
    }


    public  static function SaltPwd(string $password): array {

        // Génère un sel aléatoire

        $salt = random_bytes(32);

        // Hash le mot de passe avec le sel

        $hashedPassword = hash('sha512', $password . $salt);
        return ['password' => $hashedPassword, 'salt' => $salt];
    }

    public static function createAccount(string $guid, string $password, string $salt) {

            $db = new Database();
            $db->testConnection();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Vérifie si l'email existe déjà dans la base de données

                $existingUser = Users::GetAccountByMail($email);
                if ($existingUser) {
                    echo "Cet email est déjà utilisé.";
                    return;
                }

                // Génère le GUID unique

                $guid = uniqid();

                // Crée un objet Account

                $account = new Account($guid, $password, '');

                // Sale le mot de passe

                $result = $account->SaltPwd($password);
                $hashedPassword = $result['password'];
                $salt = $result['salt'];

                // Autres opérations avec le mot de passe hashé...

                echo "Youhou mot de passe salé : " . $hashedPassword;

                // Coconnexion bdd

                try {
                    $link = $db->getConnection();
                } catch (Exception $e) {
                    // Erreur de coconnexion
                }

                // Insère les autres données dans la table accounttmp

                $stmtTmp = $link->prepare("INSERT INTO accounttmp (guid, pwd, salt) VALUES (?, ?, ?)");
                $stmtTmp->bindParam(1, $guid);
                $stmtTmp->bindParam(2, $hashedPassword);
                $stmtTmp->bindParam(3, $salt);
                $stmtTmp->execute();
                $stmtTmp->closeCursor();


                // Génère un OTP et une validité pour celui-ci

                $otp = Account_otp::generateOTP(8);

                $validity = date('Y-m-d H:i:s', strtotime('+1 MINUTE'));

                // Enregistre l'OTP dans la table accountotp puis vérifie sa confirmation



                Account_otp::createOTP($guid, $otp, $validity);

                // Insère l'email dans la table Users en utilisant la méthode CreateUser

                Users::CreateUser($guid, $email);


                // Insère les autres données dans la table account

                $stmt1 = $link->prepare("INSERT INTO account (guid, password, salt) VALUES (?, ?, ?)");
                $stmt1->bindParam(1, $guid);
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

    public static function connexion(string $email, string $password, string $hashedPassword, string $salt): ?Users
    {
        new Log("Connexion de l'utilisateur" . $email);

        // Récupère le compte en fonction de l'email

        $account = Users::GetAccountByMail($email);

        // Va comparer les mots de passe et retourner la page connectée si l'association est bonne

        if (!Account::comparePassword($password, $hashedPassword, $salt)) {
            return null;
        }
        else
        {
            return $account;
        }
    }

    public static function confirmOTP(string $guid, string $otp) {

        $db = new Database();
        $db->testConnection();

        // Vérifie si l'OTP reçu concorde bien avec celui rentré par l'utilisateur

        $stmt = $db->getConnection()->prepare("SELECT ao.otp, ao.validity, u.email FROM accountotp AS ao
                INNER JOIN users AS u ON ao.guid = u.guid
                WHERE ao.otp = ?");
        $stmt->bindValue(1, $otp);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result && $result['otp'] === $otp)
        {
            // Vérifie si l'OTP est toujours valide

            $validity = strtotime($result['validity']);
            $currentTimestamp = time();

            if ($validity > $currentTimestamp)
            {
                // Si toujours valide alors crée l'OTP et la validité dans la table accountotp

                new Log("OTP confirmé");
                echo "OTP confirmé";
                Account_otp::createOTP($guid, $otp, $result['validity']);

                // Donne les droits à l'utilisateur

                $stmtAccount = $db->getConnection()->prepare("INSERT INTO account (guid, password, salt) SELECT guid, pwd, salt FROM accounttmp WHERE guid = ?");
                $stmtAccount->bindValue(1, $guid);
                $stmtAccount->execute();

                // Crée l'utilisateur dans la table users

                $stmtUsers = $db->getConnection()->prepare("INSERT INTO users (guid, email) VALUES (?, ?)");
                $stmtUsers->bindValue(1, $guid);
                $stmtUsers->execute();

                // Supprime l'OTP de la table accountotp

                $stmtDelete = $db->getConnection()->prepare("DELETE FROM accounttmp WHERE guid = ?");
                $stmtDelete->bindValue(1, $guid);
                $stmtDelete->execute();

                echo "Nouveaux enregistrements créés avec succès.";
            }
            else
            {
                echo "La validité de l'OTP est dépassée.";
            }
        }
        else
        {
            echo "L'OTP n'est pas valide.";
        }
    }
}