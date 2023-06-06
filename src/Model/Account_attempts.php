<?php

namespace Account_attempts;


class Account_attempts
{
    private string $guid;
    private int $attempt_at;

    public function __construct(int $guid, int $attempt_at)
    {
        $this->guid = $guid;
        $this->attempt_at = $attempt_at;
    }

    public function get_guid(): int
    {
        return $this->guid;
    }

    public function get_attempt(): int
    {
        return $this->attempt_at;
    }


}