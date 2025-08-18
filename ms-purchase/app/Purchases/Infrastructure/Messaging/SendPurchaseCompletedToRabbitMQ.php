<?php
namespace App\Purchases\Infrastructure\Messaging;

use App\Purchases\Domain\Events\PurchaseCompleted;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\Log;

/**
 * Listener to handle the message from the message broker
 * Class InventoryReadyPreapareListener
 * @package App\Purchases\Infrastructure\Messaging
 */
class SendPurchaseCompletedToRabbitMQ
{
    /**
    * Send the purchase completed
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function handle(PurchaseCompleted $event)
    {
        $completePurchase = [
            'order_id' => $event->purchaseResult['order_id'],
            'status' => $event->purchaseResult['status'],
            'ingredients' => $event->purchaseResult['ingredients']
        ];
        Log::info('Sending message to message broker purchase_response_queue');
        Amqp::publish('routing-key-4', json_encode($completePurchase), [
            'queue' => 'purchase_response_queue',
            'exchange' => 'exchange_purchase_response',  
            'exchange_type' => 'direct'         
        ]);
    }
}
