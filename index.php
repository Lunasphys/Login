<?php



require_once 'src/Pdo/database.php';
require_once 'src/Model/log.php';
require_once 'src/Model/account.php';
require_once 'src/Model/users.php';
require_once 'src/Controller/signUp.php';


use Database\database;
use Account\account;
use Log\log;
use SignUp\signUp;
use Users\users;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        signUp::execute();
    } catch (Exception $e) {
        echo "Une erreur s'est produite : " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription</title>
    <script src="src/Script/PopUpOTP.js"></script>
    <link href="src/Style/PoPUpOTP.css"></link>
</head>

<body>
<!-- <form action="../Controller/SignUp.php" method="post">-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label>Email:<input type="text" name="email"></label><br>
    <label>Mot de passe:<input type="password" name="password"></label><br>
    <label>Confirmer le mot de passe:<input type="password" name="password2"></label><br>
    <input type="submit" value="Inscription">
    <a href="">J'ai déjà un compte</a>
</form>
</body>
</html>

