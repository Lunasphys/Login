
<?php

ob_start();

?>

<!DOCTYPE html>
    <html lang="">
        <head>
            <title>Page d'accueil</title>
        </head>
        
    <body>
        <h1>Bienvenue sur notre site !</h1>
        <p>Choisissez une action :</p>
        <ul>
            <li><a href="Template/signUp.php">Inscription</a></li>
            <li><a href="signIn.php">Connexion</a></li>
        </ul>
    </body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>