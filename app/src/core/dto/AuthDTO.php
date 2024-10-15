<?php

namespace toubeelib\core\dto;

class AuthDTO
{
    public string $id;
    public string $email;
    public int $role;
    public string $token;

    public function __construct(string $id, string $email, int $role, string $token)
    {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
        $this->token = $token;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
