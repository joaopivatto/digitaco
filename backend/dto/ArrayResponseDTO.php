<?php

namespace dto;

require_once __DIR__ . "/MessageResponseDTO.php";

class ArrayResponseDTO extends MessageResponseDTO
{

    private array $data;

    /**
     * @param array $data
     */
    public function __construct(string $message, int $statusCode, array $data)
    {
        parent::__construct($message, $statusCode);
        $this->data = $data;
    }

    public function jsonSerialize(): array
    {
        $parentData = parent::jsonSerialize();
        $childData = [
            'data' => $this->data
        ];
        return array_merge($parentData, $childData);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }


}