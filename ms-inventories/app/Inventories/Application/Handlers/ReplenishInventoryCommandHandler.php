<?php

namespace App\Inventories\Application\Handlers;

use App\Inventories\Application\Commands\ReplenishInventoryCommand;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\Log;

/**
 * Command handler to replenish the inventory
 * Class ReplenishInventoryCommandHandler
 * @package App\Inventories\Application\Handlers
 */
class ReplenishInventoryCommandHandler
{
    /**
     * Send a purchase request to the message broker
     * @param ReplenishInventoryCommand $command
     */
    public function handle(ReplenishInventoryCommand $command)
    {

        Log::info("Purchase request received for order {$command->orderId}");
        $responseMessage = [
            'order_id' => $command->orderId,
            'missing_ingredients' =>  $command->ingredientsNeeded,
        ];

        Log::info("Purchase in progress for missing ingredients: " . json_encode($command->ingredientsNeeded));

        Log::info('Sending message to message broker purchase_request_queue');
        Amqp::publish('routing-key-3', json_encode($responseMessage), [
            'queue' => 'purchase_request_queue',
            'exchange' => 'exchange_purchase_request', 
            'exchange_type' => 'direct'
        ]);
    }
}
