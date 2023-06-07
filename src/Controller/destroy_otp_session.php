<?php

namespace Login;

use Database\database;
use Exception;
use Users\users;
use Account\account;
use Log\log;

session_start();
unset($_SESSION['otp']);
echo "OTP session destroyed successfully.";
