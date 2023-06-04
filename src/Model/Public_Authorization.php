<?php

namespace Public_Authorization;

class Public_Authorization
{
    private string $Web_Service;

    public function __construct(string $Web_Service)
    {
        $this->Web_Service = $Web_Service;
    }

    public function get_Web_Service(): string
    {
        return $this->Web_Service;
    }
}