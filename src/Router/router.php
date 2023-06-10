<?php
namespace Router;


require_once 'src/Model/account.php';
require_once 'src/Pdo/database.php';
require_once 'src/Controller/signUpController.php';
require_once 'src/Model/account_tmp.php';
require_once 'src/Controller/otpVerificationController.php';
require_once 'src/Controller/signInController.php';
require_once 'src/Model/account_attempts.php';


namespace Router;



class router
{
    public function __construct()
    {
        $this->route();
    }

    private function route()
    {
        $uri = $_SERVER["REQUEST_URI"];



        switch ($uri) {
            case '/':

                if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['logout']) && $_POST['logout'] === 'true') {
                    // Supprime le cookie "logged_in"
                    setcookie('logged_in', '', time() - 3600);
                    header('Location: direction.php');
                    exit();
                } else {
                    require_once 'Template/isConnected.php';
                }
                break;
            case '/signIn.php':
                require_once 'Template/signIn.php';
                break;
            case '/signUp.php':
                require_once 'Template/signUp.php';
                break;
            case '/otpVerification.php':
                require_once 'Template/otpVerification.php';
                break;
            case '/isConnected.php':
                require_once 'Template/isConnected.php';
                break;
            default:
                http_response_code(404);
                echo "La page demandée n'existe pas.";
        }
    }
}