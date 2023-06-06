<?php

namespace Account_otp;

use Database\Database;

class Account_otp
{
    private string $guid;
    private string $otp;

    private string $validity;

    public static function createOTP(string $guid, string $otp, string $validity)
    {
        // Rentre l'OTP, le guid et la validité dans la base de données

        $db = new Database();
        $db->testConnection();

        // Converti validity en un format approprié pour la base de donnée

        $validity = date('Y-m-d H:i:s', strtotime($validity));

        // Rentre l'OTP, le guid et la validité dans la base de données

        $stmt = $db->getConnection()->prepare("INSERT INTO `accountotp` (`guid`, `otp`, `validity`) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $guid);
        $stmt->bindValue(2, $otp);
        $stmt->bindValue(3, $validity);
        $stmt->execute();
    }

    public static function generateOTP($n): string
    {
        // Prend une base de string qui possède tous les chiffres

        $base = "0123456789abcdefghijklmnopqrstuvwxyz";
        $shuffle = str_shuffle($base);

        // Stock le résultat dans une string vide

        $result = "";

        // Prend un chiffre aléatoire dans la string shuffle et le stock dans la string vide


        for ($i = 0; $i < $n; $i++) {
            $result .= substr($shuffle, rand() % strlen($shuffle), 1);
        }

        return $result;
    }

}