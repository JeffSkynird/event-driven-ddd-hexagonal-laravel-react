<?php

namespace App\Inventories\Domain\DTOs;

/**
 * Generic response list for the API
 * DTO GenericResponseList
 * @package App\Inventories\Domain\DTOs
 */
class GenericResponseList
{
    public string $success;
    public string $message;
    public array $data;

    public function __construct(string $success, string $message, array $data)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }
}
