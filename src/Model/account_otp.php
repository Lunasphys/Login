<?php

namespace Account_otp;

use Database\database;
use Users\users;


class account_otp
{
    public function __construct(string $guid, string $otp, string $validity)
    {
    }
    public static function createOTP(string $guid, string $otp, string $validity) : ?account_otp
    {
        $db = new database();
        $db->testConnection();



        $stmt = $db->getConnection()->prepare("INSERT INTO `accountotp` (`guid`, `otp`, `validity`) VALUES (:guid, :otp, :validity)");
        $stmt->bindValue(':guid', $guid);
        $stmt->bindValue(':otp', $otp);
        $stmt->bindValue(':validity', $validity);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result) {
            return new account_otp($result["guid"], $result["otp"], $result["validity"]);
        } else {
            return null;
        }
    }

    public static function generateOTP($n): string
    {
        $base = "0123456789abcdefghijklmnopqrstuvwxyz";
        $shuffle = str_shuffle($base);
        $result = "";

        for ($i = 0; $i < $n; $i++) {
            $result .= substr($shuffle, rand() % strlen($shuffle), 1);
        }

        return $result;
    }




    /**
     * @throws \Exception
     */


    public static function deleteOTP(string $guid): bool
    {
        $db = new database();
        $db->testConnection();

        $stmt = $db->getConnection()->prepare("DELETE FROM `accountotp` WHERE `guid` = :guid");
        $stmt->bindValue(':guid', $guid);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result !== false) {
            return true;
        } else {
            return false;
        }
    }

    public static function getOTPValidity(string $guid): ?string
    {
        $db = new database();
        $db->testConnection();

        $stmt = $db->getConnection()->prepare("SELECT `validity` FROM `accountotp` WHERE `guid` = :guid");
        $stmt->bindValue(':guid', $guid);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result !== false) {
            return $result["validity"];
        } else {
            return null;
        }
    }

    public static function getOTPSecondsRemaining(string $guid): ?int
    {

        $validity = self::getOTPValidity($guid);
        if ($validity !== null) {
            $currentTimestamp = time();
            $validityTimestamp = strtotime($validity);
            $secondsRemaining = $validityTimestamp - $currentTimestamp;

            if ($secondsRemaining <= 0) {
                users::deleteUsers($guid);
                echo "Votre code OTP a expiré, veuillez vous réinscrire";
                return 0;
            } else {
                return $secondsRemaining;
            }
        } else {
            return null;
        }
    }

}