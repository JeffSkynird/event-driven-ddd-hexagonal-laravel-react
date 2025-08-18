<?php

namespace App\Inventories\Application\Listeners;

use App\Inventories\Domain\Enums\InventoryStatus;
use App\Inventories\Domain\Events\StockReplenishedEvent;
use App\Inventories\Domain\Repositories\InventoryRepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Listener to update the inventory
 * Class UpdateInventoryListener
 * @package App\Inventories\Application\Listeners
 */
class UpdateInventoryListener
{
    private $inventoryRepository;

    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * Dispatch ingredients and update the inventory
     *
     * @param StockReplenishedEvent $event
     */
    public function handle(StockReplenishedEvent $event)
    {
        Log::info("Updating inventory with {$event->quantity} units of {$event->ingredientName}");

        $ingredient = $this->inventoryRepository->findByName($event->ingredientName);
        $totalQuantity = $event->totalQuantity;
        if ($ingredient) {
            // Increase the quantity of the ingredient
            $ingredient->increaseQuantity($event->quantity);

            $this->inventoryRepository->updateQuantity($ingredient);

            Log::info("Stock replenished for {$event->ingredientName}");
            // Log the inventory movement with type 'purchased'
            $this->inventoryRepository->logInventoryMovement($ingredient->id, $event->quantity, InventoryStatus::PURCHASED->value);

            // Dispatch the available ingredients to the kitchen
            $ingredient->decreaseQuantity($totalQuantity);
            $this->inventoryRepository->updateQuantity($ingredient);

            // Log the inventory movement with type 'usage'
            $this->inventoryRepository->logInventoryMovement($ingredient->id, -$totalQuantity, InventoryStatus::USAGE->value);

            Log::info("{$totalQuantity} {$event->ingredientName} dispatched successfully");
        } else {
            Log::error("Ingredient {$event->ingredientName} not found in inventory.");
        }
    }
}
