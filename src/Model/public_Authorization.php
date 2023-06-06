<?php

namespace Public_Authorization;

class public_Authorization
{
    private int $id;
    private string $web_service;

    public function __construct(int $id, string $web_service)
    {
        $this->id = $id;
        $this->web_service = $web_service;
    }

    public function get_Web_Service(): string
    {
        return $this->web_service;
    }
}