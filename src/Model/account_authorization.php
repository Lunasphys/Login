<?php

namespace Account_authorization;

class account_authorization
{
    private string $guid;
    private string $web_service;

    public function __construct(int $guid, string $web_service)
    {
        $this->guid = $guid;
        $this->web_service = $web_service;
    }

    public function get_user_id(): int
    {
        return $this->guid;
    }
}