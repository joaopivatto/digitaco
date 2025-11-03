<?php

namespace backend\model;

class League
{
    public int $id;
    public string $nome;
    public string $creatorId;
    public string $createdAt;

    public function __construct($id, $nome, $creatorId, $createdAt)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->creatorId = $creatorId;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCreatorId(): string
    {
        return $this->creatorId;
    }

    public function setCreatorId(string $creatorId): void
    {
        $this->creatorId = $creatorId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}