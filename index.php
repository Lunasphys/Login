<?php

session_start();

require_once 'src/Pdo/database.php';
require_once 'src/Model/log.php';
require_once 'src/Model/account.php';
require_once 'src/Model/users.php';
require_once 'src/Controller/signUpController.php';


use Database\database;
use Account\account;
use Log\log;
use SignUp\signUpController;
use Users\users;



$uri = substr(parse_url($_SERVER['REQUEST_URI'])["path"],1);
$method = $_SERVER['REQUEST_METHOD'];
$isGetMet = $method === 'GET';

$view = "Template/";

if ($uri === 'signUp')
{
    $view .= "signUp.php";

    if (isset($_POST["signUpController"]))
    {
        echo signUpController::execute();
    }
}
else if ($uri === 'signIn') {
    $view .= "signIn.php";
}



