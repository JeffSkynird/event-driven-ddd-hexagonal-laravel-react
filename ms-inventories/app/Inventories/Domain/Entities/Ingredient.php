<?php

namespace App\Inventories\Domain\Entities;

class Ingredient
{
    public $id;
    public $name;
    public $availableQuantity;
    public $createdAt;
    public $updatedAt;

    public function __construct($id, $name, $availableQuantity, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->availableQuantity = $availableQuantity;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Método para aumentar la cantidad disponible
    public function increaseQuantity($amount)
    {
        $this->availableQuantity += $amount;
    }

    // Método para disminuir la cantidad disponible
    public function decreaseQuantity($amount)
    {
        $this->availableQuantity -= $amount;
    }

}
