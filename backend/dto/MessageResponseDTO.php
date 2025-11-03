<?php

namespace dto;

class MessageResponseDTO
{

    private string $message;

    private int $statusCode;

    /**
     * @param string $message
     */
    public function __construct(string $message, int $statusCode)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function jsonSerialize(): array {
        return [
            'message' => $this->message,
            'statusCode' => $this->statusCode
        ];
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

}