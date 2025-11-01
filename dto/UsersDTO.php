<?php

namespace dto;

class UsersDTO
{
    public int $id;
    public string $name;
    public string $email;

    public function __construct($user)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function toArray(): array{
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email
        ];
    }
}