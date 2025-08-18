<?php

namespace App\Kitchen\Infrastructure\Messaging;

use App\Kitchen\Domain\Enums\MessageQueueStatus;
use App\Kitchen\Domain\Enums\OrderStatus;
use App\Kitchen\Domain\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Listener to handle the message from the message broker
 * Class InventoryResponseListener
 * @package App\Kitchen\Infrastructure\Messaging
 */
class InventoryResponseListener
{
    private $orderRepository;
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    /**
    * Listen to the message from the message broker listener
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function listen($message)
    {
        $data = json_decode($message->body, true);

        $orderId = $data['order_id'];
        $status = $data['status'];

        Log::info("Order  {$orderId} status: {$status}");

        if ($status === MessageQueueStatus::INGREDIENTS_AVAILABLE->value) {
            Log::info("All ingredients are available for order {$orderId}. Proceeding with preparation.");
            // Prepare the dish for the order
            $this->prepareDish($orderId);
        } elseif ($status === MessageQueueStatus::MISSING_INGREDIENTS->value) {
            Log::warning("Ingredients missing for order {$orderId}: Action: " . $data['action']);
            Log::warning("Ingredients missing: " . json_encode($data['missing_ingredients']));
            // Handle missing ingredients
            $this->handleMissingIngredients($orderId);
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

    /**
    * Change the status of the order to restocking ingredients
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    private function handleMissingIngredients($orderId)
    {
        $this->orderRepository->updateOrderStatus($orderId, OrderStatus::RESTOCKING->value);
        Log::info("Order {$orderId} is restocking ingredients.");
    }
}
