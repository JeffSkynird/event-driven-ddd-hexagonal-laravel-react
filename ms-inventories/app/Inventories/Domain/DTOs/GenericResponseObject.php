<?php

namespace App\Inventories\Domain\DTOs;

/**
 * Generic response objet for the API
 * DTO GenericResponseObject
 * @package App\Inventories\Domain\DTOs
 */
class GenericResponseObject
{
    public string $success;
    public string $message;
    public mixed $data;

    public function __construct(string $success, string $message, mixed $data)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }
}
