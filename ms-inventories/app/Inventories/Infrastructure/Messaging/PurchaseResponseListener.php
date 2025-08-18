<?php

namespace App\Inventories\Infrastructure\Messaging;

use App\Inventories\Domain\Events\StockReplenishedEvent;
use App\Inventories\Domain\Repositories\InventoryRepositoryInterface;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\Log;

/**
 * Listener to handle the message from the message broker
 * Class InventoryRequestListener
 * @package App\Inventories\Infrastructure\Messaging
 */
class PurchaseResponseListener
{
    protected $inventoryRepository;

    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }
    /**
    * Listen for messages from purchase responses
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function listen($message)
    {
        $data = json_decode($message->body, true);

        $orderId = $data['order_id'];
        $ingredients = $data['ingredients'];
        $status = $data['status'];
      
        // If the status is success, update inventory
        if ($status === 'purchase_completed') {
            foreach ($ingredients as $ingredient) {
                $ingredientName = $ingredient['ingredient'];
                $quantityBought = $ingredient['quantityReceived'];
                $quantityRequested = $ingredient['quantityRequested'];
                $totalQuantity = $ingredient['totalQuantity'];

                Log::info("Restocking {$quantityBought} units of {$ingredientName}");
                // Emit a event for replenishing
                event(new StockReplenishedEvent($ingredientName, $quantityBought,$quantityRequested,$totalQuantity));
            }
            $this->notifyKitchen($orderId,'ingredients_available');
        }else if($status === 'purchase_error'){
            $this->notifyKitchen($orderId,'error');
        }else{
            Log::error("Purchase failed for order {$orderId}");
        }
    }
    /**
    * Notify to kitchen for ingredients purchase
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    private function notifyKitchen($orderId,$status)
    {
        $message = [
            'order_id' => $orderId,
            'status' => $status
        ];
        Log::info("All ingredients are ready for order {$orderId}");
        Log::info('Sending message to message broker kitchen_order_queue');

        Amqp::publish('routing-key-5', json_encode($message), [
            'queue' => 'kitchen_order_queue', 
            'exchange' => 'exchange_kitchen', 
            'exchange_type' => 'direct'
        ]);
    }
}
