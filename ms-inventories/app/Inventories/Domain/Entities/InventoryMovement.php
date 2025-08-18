<?php

namespace App\Inventories\Domain\Entities;

class InventoryMovement
{
    public $id;
    public $ingredientId;
    public $quantity;
    public $type; // "purchase", "usage", "restock"
    public $createdAt;

    public function __construct($id, $ingredientId, $quantity, $type, $createdAt)
    {
        $this->id = $id;
        $this->ingredientId = $ingredientId;
        $this->quantity = $quantity;
        $this->type = $type;
        $this->createdAt = $createdAt;
    }
}
