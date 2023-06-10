<?php
use SignUpController\signUpController;

require_once '../src/Controller/signUpController.php';
require_once '../src/Pdo/database.php';
require_once '../src/Model/account.php';
require_once '../src/Model/account_otp.php';
require_once '../src/Model/account_tmp.php';
require_once '../src/Model/users.php';

// Vérifier si les cookies sont activés

if (isset($_COOKIE['test_cookie'])) {
    echo "Les cookies sont activés.";
} else {
    echo "Les cookies sont désactivés.";
}
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    try {
        $result = SignUpController::execute();
        if ($result === true) {
            echo "Compte créé";
        } else {
            echo $result;
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}




// Définir un cookie de test
setcookie('test_cookie', 'test_value', time() + 3600);


ob_start();
?>
    <!DOCTYPE html>
    <html lang="">
    <head>
        <title>Inscription</title>
        <script>
            window.onload = function() {
                let otp = "<?php echo $_COOKIE['otp'] ?? ''; ?>";
                let isFormSubmitted = "<?php echo $_SESSION['form_submitted'] ?? ''; ?>";
                if (otp && isFormSubmitted) {
                    showOTP(otp);
                    fetch('destroy_otp_session.php').then(function(response) {
                        return response.text();
                    }).then(function(data) {
                        console.log(data);
                    }).catch(function(err) {
                        console.error(err);
                    });
                }
            }

            function verifyOTP() {
                let otpInput = document.getElementById("otpInput").value;
                let otp = "<?php echo $_COOKIE['otp'] ?? ''; ?>";
                if (otpInput === otp) {
                    return true;
                } else {
                    alert("Code OTP incorrect");
                    return false;
                }
            }
        </script>
    </head>
    <body>
    <form action="" method="post" id="signUpForm">
        <label>Email:<input type="text" name="email" required></label><br>
        <label>Mot de passe:<input type="password" name="password" required></label><br>
        <label>Confirmer le mot de passe:<input type="password" name="password2" required></label><br>
        <input type="submit" value="Inscription" >
        <a href="">J'ai déjà un compte</a>
    </form>
    </body>
    </html>
<?php
$content = ob_get_clean();
echo $content;
?>