<?php

namespace App\Kitchen\Domain\Services;

use App\Kitchen\Domain\Repositories\DishRepositoryInterface;
use App\Kitchen\Domain\Repositories\OrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Service class to manage the general kitchen operations
 * Class KitchenService
 * @package App\Kitchen\Domain\Services
 */
class KitchenService
{
    private OrderRepositoryInterface $orderRepository;
    private DishRepositoryInterface $dishRepository;


    public function __construct(OrderRepositoryInterface $orderRepository, DishRepositoryInterface $dishRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->dishRepository = $dishRepository;
    }
    /**
    * Get all orders
    * @return mixed
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getOrders()
    {
        return $this->orderRepository->getOrders();
    }
    /**
    * Get orders paginated
    * @return mixed
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getOrdersPaginated($perPage)
    {
        return $this->orderRepository->getOrdersPaginated($perPage);
    }
    /**
    * Get recipes paginated
    * @return mixed
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getRecipesPaginated($perPage)
    {
        return $this->dishRepository->getRecipesPaginated($perPage);
    }
    /**
    * Get if the order is allowed to retry in case of error
    * @return mixed
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function isOrderAllowedToRetry(?int $orderId): bool
    {
        if(is_null($orderId)){
            return true;
        }
        // Verificar si la orden ya está en estado "PREPARING" o "COMPLETED"
        $orderStatus = $this->orderRepository->getOrderStatusById($orderId);
        if (is_null($orderStatus)) {
            Log::error("Order #{$orderId} not found");
            throw new Exception("Order #{$orderId} not found");
        }
        if ($orderStatus !== 'error') {
            Log::warning("Order #{$orderId} must be in error status to retry");
            throw new Exception("Order #{$orderId} must be in error status to retry");
        }
        return true;
    }

}
