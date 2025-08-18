<?php

namespace App\Inventories\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event that represents the waiting for the stock
 * Event StockReplenishedEvent
 * @package App\Inventories\Domain\Events
 */
class WaitingForStockEvent
{
    use Dispatchable, SerializesModels;

    public $ingredientName;

    public function __construct(string $ingredientName)
    {
        $this->ingredientName = $ingredientName;
    }
}
