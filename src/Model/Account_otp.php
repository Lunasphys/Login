<?php

namespace Account_otp;

class Account_otp
{
    private string $guid;
    private string $WebService;

    public function __construct(int $guid, string $WebService)
    {
        $this->guid = $guid;
        $this->WebService = $WebService;
    }

    public function get_guid(): int
    {
        return $this->guid;
    }
}