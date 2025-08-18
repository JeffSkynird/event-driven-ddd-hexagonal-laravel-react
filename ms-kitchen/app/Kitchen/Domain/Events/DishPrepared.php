<?php

namespace App\Kitchen\Domain\Events;

use App\Kitchen\Domain\Entities\Dish;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;

/**
 * Event that represents the dish prepared
 * Event DishPrepared
 * @package App\Kitchen\Domain\Events
 */
class DishPrepared
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userData;
    public int $orderId;
    public Dish $dish;

    public function __construct(int $orderId, Dish $dish)
    {
        $this->orderId = $orderId;
        $this->dish = $dish;
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
