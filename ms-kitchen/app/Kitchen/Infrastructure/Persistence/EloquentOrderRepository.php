<?php

namespace App\Kitchen\Infrastructure\Persistence;

use App\Kitchen\Domain\Entities\Order;
use App\Kitchen\Domain\Enums\OrderStatus;
use App\Kitchen\Domain\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Implementation of the OrderRepositoryInterface using Eloquent
 * Class EloquentOrderRepository
 * @package App\Kitchen\Infrastructure\Persistence
 */
class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function createOrder($dishId)
    {
        Log::info('Creating order for dish with ID: ' . $dishId);
        return DB::table('orders')->insertGetId([
            'dish_id' => $dishId,
            'order_status' => OrderStatus::PENDING->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    public function updateOrderStatus($orderId, $status)
    {
        Log::info("Updating order with ID: $orderId to status: $status");
        DB::table('orders')
            ->where('id', $orderId)
            ->update([
                'order_status' => $status,
                'updated_at' => now(),
            ]);
    }
    public function getOrderStatusById($orderId)
    {
        return DB::table('orders')
            ->where('id', $orderId)
            ->value('order_status');
    }
    public function getOrders()
    {
        return Order::with('dish.ingredients')->orderBy('id', 'desc')->get();
    }
    public function getOrdersPaginated($perPage)
    {
        return Order::with('dish.ingredients')->orderBy('id', 'desc')->paginate( $perPage);
    }
}
