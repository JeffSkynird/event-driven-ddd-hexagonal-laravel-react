<?php

namespace App\Purchases\Infrastructure\Messaging;

use App\Purchases\Application\Commands\InitiatePurchaseCommand;
use App\Purchases\Application\Handlers\PurchaseCommandHandler;
use Illuminate\Support\Facades\Log;

/**
 * Listener to handle the message from the message broker
 * Class InventoryReadyPreapareListener
 * @package App\Purchases\Infrastructure\Messaging
 */
class PurchaseRequestListener
{
    protected $commandHandler;

    public function __construct(PurchaseCommandHandler $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }
    /**
    * Listen to purchase request messages
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function listen($message)
    {
        $orderData = json_decode($message->body, true);
        Log::info('Starting purchase process for order ' . $orderData['order_id']);
        Log::info('Missing ingredients: ' . json_encode($orderData['missing_ingredients']));
        $command = new InitiatePurchaseCommand($orderData['missing_ingredients'], $orderData['order_id']);
        $this->commandHandler->handle($command);
    }
}
