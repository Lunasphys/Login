<?php
namespace Account_tmp;
class Account_tmp
{
    private string $guid;
    private string $password;
    private string $salt;

    public function __construct(int $guid, string $password, string $salt)
    {
        $this->guid = $guid;
        $this->password = $password;
        $this->Salt = $salt;
    }


}