<?php
namespace Account;


use Database\database;
use Exception;
use Log\log;
use Users\users;
use Account_otp\account_otp;

require_once __DIR__ . "/account_otp.php";


class account {
    // Variable
    private string $guid;
    private string $password;
    private string $salt;

    // Constructor

    public function __construct(string $guid, string $password, string $salt) {
        $this->guid = $guid;
        $this->password = $password;
        $this->salt = $salt;
        new log ("Construction en cours");
    }

    //Récupère le guid et le met dans account

    public static function getGUID(): string {
        $guid = uniqid();
        return $guid;
    }


    public  static function SaltPwd(string $password): array {

        // Génère un sel aléatoire

        $salt = random_bytes(32);

        // Hash le mot de passe avec le sel

        $hashedPassword = hash('sha512', $password . $salt);
        return ['password' => $hashedPassword, 'salt' => $salt];
    }

    /**
     * @throws Exception
     */
    public static function createAccount() : bool
    {

            $db = new database();
            $db->testConnection();

            // Génère le GUID unique

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Vérifie si l'email existe déjà dans la base de données

                $existingUser = users::GetAccountByMail($email);
                if ($existingUser) {
                    echo "Cet email est déjà utilisé.";
                    return false ;
                }



                // Sale le mot de passe

                $result = self::SaltPwd($password);
                $hashedPassword = $result['password'];
                $salt = $result['salt'];

                // Autres opérations avec le mot de passe hashé...

                echo "Youhou mot de passe salé : " . $hashedPassword;

                $guid = self::getGUID();

                // Coconnexion bdd

                echo "je suis là";



                // Insère l'email dans la table users en utilisant la méthode CreateUser

                users::CreateUser($guid, $email);

                new log ("Le compte va se créer dans la table accounttmp");

                $query = "INSERT INTO accounttmp (guid,  password, salt) VALUES (:guid, :password, :salt)";
                $statement = (new database())->getConnection()->prepare($query);

                if ($statement === false) {
                    throw new Exception("Erreur database");
                }

                $statement->bindParam(':guid', $guid);
                $statement->bindParam(':password', $hashedPassword);
                $statement->bindParam(':salt', $salt);
                $statement->execute();

                echo "Compte créé dans tmp";


                // Génère un OTP et une validité pour celui-ci

                $otp = account_otp::generateOTP(8);

                $validity = date('Y-m-d H:i:s', strtotime('+1 MINUTE'));

                // Enregistre l'OTP dans la table accountotp puis vérifie sa confirmation

                account_otp::createOTP($guid, $otp, $validity);



                echo "Nouveaux enregistrements créés avec succès.";
                return true;

        }
        return false;
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

    public static function connexion(string $email, string $password, string $hashedPassword, string $salt): ?users
    {
        new log("Connexion de l'utilisateur" . $email);

        // Récupère le compte en fonction de l'email

        $account = users::GetAccountByMail($email);

        // Va comparer les mots de passe et retourner la page connectée si l'association est bonne

        if (!account::comparePassword($password, $hashedPassword, $salt)) {
            return null;
        }
        else
        {
            return $account;
        }
    }

    public static function confirmOTP(string $guid, string $otp) {

        $db = new database();
        $db->testConnection();

        // Vérifie si l'OTP reçu concorde bien avec celui rentré par l'utilisateur

        $stmt = $db->getConnection()->prepare("SELECT ao.guid, ao.otp, ao.validity FROM accountotp AS ao
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

                new log("OTP confirmé");
                echo "OTP confirmé";
                account_otp::createOTP($guid, $otp, $result['validity']);

                // Déplace les informations du compte de la table accounttmp à la table account

                if(account::moveAccountFromTmp($guid)) {
                    echo "Nouveaux enregistrements créés avec succès.";
                }
                else
                {
                    echo "Erreur lors du déplacement du compte de la table temporaire.";
                }

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

    public static function moveAccountFromTmp(string $guid) : bool {
        $db = new database();

        // Déplace les informations du compte de la table accounttmp à la table account

        $query = "INSERT INTO account (guid, password, salt) SELECT guid, password, salt FROM accounttmp WHERE guid = :guid";
        $statement = $db->getConnection()->prepare($query);
        $statement->bindParam(':guid', $guid);

        if($statement->execute()) {
            // Supprime l'entrée de la table accounttmp

            $query = "DELETE FROM accounttmp WHERE guid = :guid";
            $statement = $db->getConnection()->prepare($query);
            $statement->bindParam(':guid', $guid);
            $statement->execute();
            return true;
        }
        return false;
    }
}