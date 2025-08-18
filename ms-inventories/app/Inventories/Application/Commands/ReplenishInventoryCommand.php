<?php

namespace App\Inventories\Application\Commands;

/**
 * Command to replenish the inventory
 * Class ReplenishInventoryCommand
 * @package App\Inventories\Application\Commands
 */
class ReplenishInventoryCommand
{
    public $orderId;
    public $ingredientsNeeded;

    public function __construct(array  $ingredientsNeeded,int $orderId)
    {
        $this->ingredientsNeeded = $ingredientsNeeded;
        $this->orderId = $orderId;
    }
}
