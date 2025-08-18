<?php

namespace App\Inventories\Domain\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event that represents the replenishment of the stock
 * Event StockReplenishedEvent
 * @package App\Inventories\Domain\Events
 */
class StockReplenishedEvent
{
    use Dispatchable,  InteractsWithSockets, SerializesModels;

    public $ingredientName;
    public $quantity;
    public $quantityRequested;
    public $totalQuantity;

    public function __construct(string $ingredientName, int $quantity, int $quantityRequested, int $totalQuantity)
    {
        $this->ingredientName = $ingredientName;
        $this->quantity = $quantity;
        $this->quantityRequested = $quantityRequested;
        $this->totalQuantity = $totalQuantity;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
