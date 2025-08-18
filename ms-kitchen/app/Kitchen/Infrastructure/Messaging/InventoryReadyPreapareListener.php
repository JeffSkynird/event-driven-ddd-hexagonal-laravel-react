<?php

namespace App\Kitchen\Infrastructure\Messaging;

use App\Kitchen\Domain\Enums\MessageQueueStatus;
use App\Kitchen\Domain\Enums\OrderStatus;
use App\Kitchen\Domain\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Listener to handle the message from the message broker
 * Class InventoryReadyPreapareListener
 * @package App\Kitchen\Infrastructure\Messaging
 */
class InventoryReadyPreapareListener
{
    private $orderRepository;
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    /**
    * Listen to verify if all ingredients are available for the order
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function listen($message)
    {
        $data = json_decode($message->body, true);

        $orderId = $data['order_id'];
        $status = $data['status'];

        if ($status === MessageQueueStatus::INGREDIENTS_AVAILABLE->value) {
            Log::info("All ingredients are available for order {$orderId}. Proceeding with preparation.");
            // Prepare the dish for the order
            $this->prepareDish($orderId);
        }else if($status == 'error'){
            Log::info("Error in the inventory for order {$orderId}.");
            $this->orderRepository->updateOrderStatus($orderId, OrderStatus::ERROR->value);
        }
    }
    /**
    * Change the status of the order to completed
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    private function prepareDish($orderId)
    {
        $this->orderRepository->updateOrderStatus($orderId, OrderStatus::COMPLETED->value);
        Log::info("Dish prepared for order {$orderId}.");
    }
}
