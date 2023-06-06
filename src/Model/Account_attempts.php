<?php

namespace Application\Model\Account_attempts;


class Account_attempts
{
    private string $guid;
    private int $Time;

    public function __construct(int $guid, int $Time)
    {
        $this->guid = $guid;
        $this->Time = $Time;
    }

    public function get_guid(): int
    {
        return $this->guid;
    }

    public function get_attempt(): int
    {
        return $this->Time;
    }

    public function set_GUID(int $guid): void
    {
        $this->GUID = $guid;
    }
}