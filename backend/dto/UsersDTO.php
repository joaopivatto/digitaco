<?php

namespace backend\dto;

class UsersDTO
{
    public int $id;
    public string $name;
    public string $email;
    public string $message;
    public string $status;

    public function __construct($user, $message, $status)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->message = $message;
        $this->status = $status;
    }

    public function toArray() {
        http_response_code($this->status);
        return [
            "message" => $this->message,
            "name" => $this->name,
            "email" => $this->email
        ];
    }
}