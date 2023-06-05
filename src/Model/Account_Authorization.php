<?php

namespace Account_tmp;

class Account_Authorization
{
    private string $GUID;
    private string $WebService;

    public function __construct(int $GUID, string $WebService)
    {
        $this->GUID = $GUID;
        $this->WebService = $WebService;
    }

    public function get_user_id(): int
    {
        return $this->GUID;
    }
}