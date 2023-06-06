<?php

namespace Account_tmp;

class Account_Authorization
{
    private string $guid;
    private string $WebService;

    public function __construct(int $guid, string $WebService)
    {
        $this->guid = $guid;
        $this->WebService = $WebService;
    }

    public function get_user_id(): int
    {
        return $this->guid;
    }
}