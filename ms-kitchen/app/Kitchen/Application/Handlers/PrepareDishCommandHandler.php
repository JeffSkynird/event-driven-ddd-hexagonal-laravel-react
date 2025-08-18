<?php
namespace App\Kitchen\Application\Handlers;

use App\Kitchen\Application\Commands\PrepareDishCommand;
use App\Kitchen\Domain\Enums\OrderStatus;
use App\Kitchen\Domain\Events\DishPrepared;
use App\Kitchen\Domain\Repositories\OrderRepositoryInterface;
use App\Kitchen\Domain\Services\DishFactory;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Command Handler to prepare a dish
 * Class PrepareDishCommandHandler
 * @package App\Kitchen\Application\Handlers
 */
class PrepareDishCommandHandler
{
    private DishFactory $dishFactory;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(DishFactory $dishFactory , OrderRepositoryInterface $orderRepository)
    {
        $this->dishFactory = $dishFactory;
        $this->orderRepository = $orderRepository;
    }

    public function handle(PrepareDishCommand $command)
    {
        try {
            $orderId = $command->orderId;
            Log::info('Preparing the random dish');
            // Use the Factory Method to create a random dish
            if(is_null($orderId)){
                Log::info("Creating a random dish for the order");
                $dish = $this->dishFactory->createRandonDish();
            }else{
                Log::info("Getting the dish for order # {$orderId}");
                $dish = $this->dishFactory->getDishByOrderId($orderId);
            }
            // Verify if the dish has ingredients configured
            if (count($dish->ingredients) === 0) {
                Log::info("No ingredients configured for the dish {$dish->name}");
                throw new Exception("No ingredients configured for the dish {$dish->name}");
            }
            // Create the order using the repository
            if(is_null($orderId)){
                Log::info("Creating a new order for the dish {$dish->name}");
                $orderId = $this->orderRepository->createOrder($dish->id);
            }
            Log::info("Order # {$orderId} generated for the Dish {$dish->name}");

            // Change the order status to "PREPARING"
            $this->orderRepository->updateOrderStatus($orderId, OrderStatus::PREPARING->value);

            Log::info("Order # {$orderId} status changed to PREPARING");

            // Dispatch the event DishPrepared
            event(new DishPrepared($orderId, $dish));
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
