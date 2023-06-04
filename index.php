<?php



require_once 'src/Pdo/Database.php';
require_once 'src/Model/Log.php';
require_once 'src/Model/Account.php';
require_once 'src/Model/Users.php';


use Database\Database;
use Account\Account;
use Log\Log;
use Users\Users;



$db = new Database();
$db->testConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['email'];
    $Password = $_POST['password'];

    // Génère le GUID unique
    $generatedGUID = (int) uniqid('', true);
    // Créer un objet Account
    $account = new Account($generatedGUID, $Password, '');

    // Saler le mot de passe
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


    // Insère l'email dans la table Users
    $stmt1 = $link->prepare("INSERT INTO account (GUID, Password, Salt) VALUES (?, ?, ?)");
    $stmt1->bindParam(1, $generatedGUID);
    $stmt1->bindParam(2, $hashedPassword);
    $stmt1->bindParam(3, $salt);
    $stmt1->execute();
    $stmt1->closeCursor();



    // Met l'email dans la table Users avec le GUID unique
    $stmt1 = $link->prepare("INSERT INTO Users (GUID, Email) VALUES (?, ?)");
    $stmt1->bindParam(1, $generatedGUID);
    $stmt1->bindParam(2, $Email);
    $stmt1->execute();
    $stmt1->closeCursor();


    // Fermer la connexion
    $link = null;

    echo "Nouveaux enregistrements créés avec succès.";
}
?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Inscription</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>Email:<input type="text" name="email"></label><br>
    <label>Mot de passe:<input type="password" name="password"></label><br>
    <input type="submit" value="Inscription">
</form>
</body>
</html>