<?php

namespace backend\model;

use MongoDB\BSON\Timestamp;

class Leagues
{

    private int $id;
    private string $name;
    private string $password;
    private int $creatorId;
    private Timestamp $createdAt;

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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    public function setCreatorId(int $creatorId): void
    {
        $this->creatorId = $creatorId;
    }

    public function getCreatedAt(): Timestamp
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Timestamp $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}