<?php

namespace Account_otp;

class Account_otp
{
    private int $GUID;
    private string $WebService;

    public function __construct(int $GUID, string $WebService)
    {
        $this->GUID = $GUID;
        $this->WebService = $WebService;
    }

    public function get_guid(): int
    {
        return $this->GUID;
    }
}