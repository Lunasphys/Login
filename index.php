<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'src/Router/router.php';
require_once 'src/Controller/signUpController.php';
require_once 'src/Controller/otpVerificationController.php';

use Router\Router;


$router = new Router();



