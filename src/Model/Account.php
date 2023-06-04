<?php
namespace Account;

use Log\Log;


class Account {
    // Variable
    private int $GUID;
    private string $Password;
    private string $Salt;

    // Constructor

    public function __construct(int $GUID, string $Password, string $Salt) {
        $this->GUID = $GUID;
        $this->Password = $Password;
        $this->Salt = $Salt;
        new Log ("Compte créé");
    }

    // Main fonction
    //Récupère le guid et le met dans account

    public function getGUID(): int {
        return $this->GUID;
    }
    public function SaltPwd(string $Password): array {
        $this->Password = $Password;
        try {
            $this->Salt = random_bytes(32);
        } catch (\Exception $e) {
        }
        $saltedPassword = hash('sha512', $this->Password . $this->Salt);
        return [
            'password' => $saltedPassword,
            'salt' => $this->Salt
        ];
    }


}