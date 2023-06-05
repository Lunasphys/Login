<?php
namespace Account_tmp;
class Account_tmp
{
    private string $GUID;
    private string $Password;
    private string $Salt;

    public function __construct(int $GUID, string $Password, string $Salt)
    {
        $this->GUID = $GUID;
        $this->Password = $Password;
        $this->Salt = $Salt;
    }


}