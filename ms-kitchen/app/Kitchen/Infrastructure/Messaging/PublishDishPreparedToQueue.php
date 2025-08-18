<?php

namespace App\Kitchen\Infrastructure\Messaging;

use App\Kitchen\Domain\Events\DishPrepared;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\Log;

/**
 * Class PublishDishPreparedToQueue
 * @package App\Kitchen\Infrastructure\Messaging
 */
class PublishDishPreparedToQueue
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
    * Send the message to the message broker
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function handle(DishPrepared $event)
    {
        $message = [
            'order_id' => $event->orderId,
            'dish' => $event->dish['name'], 
            'ingredients' => array_map(function ($ingredient) {
                return [
                    'name' => $ingredient['name'], 
                    'quantity' => $ingredient['pivot']['quantity'], 
                ];
            }, $event->dish['ingredients']->toArray()) 
        ];
        Log::info("Order {$event->orderId} prepared : " . json_encode($message));
        Log::info('Sending message to message broker inventory_request_queue');
        Amqp::publish('routing-key-1', json_encode($message), [
            'queue' => 'inventory_request_queue',
            'exchange' => 'exchange_requests',  
            'exchange_type' => 'direct'        
        ]);
    }
}