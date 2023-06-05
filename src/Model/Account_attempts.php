<?php

namespace Application\Model\Account_attempts;


class Account_attempts
{
    private string $GUID;
    private int $Time;

    public function __construct(int $GUID, int $Time)
    {
        $this->GUID = $GUID;
        $this->Time = $Time;
    }

    public function get_guid(): int
    {
        return $this->GUID;
    }

    public function get_attempt(): int
    {
        return $this->Time;
    }

    public function set_GUID(int $GUID): void
    {
        $this->GUID = $GUID;
    }
}