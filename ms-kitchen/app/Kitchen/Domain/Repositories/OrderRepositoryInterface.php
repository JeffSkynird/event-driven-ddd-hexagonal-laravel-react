<?php

namespace App\Kitchen\Domain\Repositories;

/**
 * Interface to define the methods that the DishRepository must implement
 * Interface DishRepositoryInterface
 * @package App\Kitchen\Domain\Repositories
 */
interface OrderRepositoryInterface
{
    public function createOrder($dishId);
    public function updateOrderStatus($orderId, $status);
    public function getOrderStatusById($orderId);
    public function getOrders();
    public function getOrdersPaginated($perPage);
}
