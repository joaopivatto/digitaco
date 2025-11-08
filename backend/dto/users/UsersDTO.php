<?php

namespace dto\users;

require_once __DIR__ . "/../MessageResponseDTO.php";

use dto\MessageResponseDTO;

class UsersDTO extends MessageResponseDTO
{
    private int $id;

    private string $name;

    private string $email;

    public function __construct(string $message, int $statusCode, int $id, string $name, string $email) {
        parent::__construct($message, $statusCode);
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function jsonSerialize(): array
    {
        $parentData = parent::jsonSerialize();
        $childData = [
            'name' => $this->name,
            'email' => $this->email
        ];
        return array_merge($parentData, $childData);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


}