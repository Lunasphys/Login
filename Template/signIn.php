<?php

use SignInController\signInController;

require_once '../src/Controller/signInController.php';
require_once '../src/Pdo/database.php';
require_once '../src/Model/account.php';
require_once '../src/Model/account_otp.php';
require_once '../src/Model/account_tmp.php';
require_once '../src/Model/users.php';
require_once '../src/Model/account_attempts.php';


if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    try {
        $result = SignInController::execute();
        if ($result === "Mot de passe correct") {
            // Redirection vers la page isConnected.php en cas de succès
            header('Location: ../../Template/isConnected.php');
            exit();
        } else {
            echo $result;
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}


setcookie('test_cookie', 'test_value', time() + 3600);

ob_start();
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign In</title>
    </head>
    <body>
    <h1>Sign In</h1>
    <form action="" method="POST">
        <label>
            Email:
            <input type="email" name="email" required>
        </label>
        <br>
        <label>
            Password:
            <input type="password" name="password" required>
        </label>
        <br>
        <button type="submit">Sign In</button>
    </form>
    </body>
    </html>

<?php
$content = ob_get_clean();
echo $content;
?>