<?php

namespace App\Inventories\Infrastructure\Messaging;

use App\Inventories\Application\Handlers\ReplenishInventoryCommandHandler;  // Añadimos el handler de compras
use App\Inventories\Application\Commands\ReplenishInventoryCommand;  // Añadimos el comando de compras
use App\Inventories\Domain\Enums\InventoryStatus;
use App\Inventories\Domain\Repositories\InventoryRepositoryInterface;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\Log;

/**
 * Listener to handle the message from the message broker
 * Class InventoryRequestListener
 * @package App\Inventories\Infrastructure\Messaging
 */
class InventoryRequestListener
{
    protected $inventoryRepository;
    protected $replenishInventoryCommandHandler;

    public function __construct(InventoryRepositoryInterface $inventoryRepository, ReplenishInventoryCommandHandler $replenishInventoryCommandHandler)
    {
        $this->inventoryRepository = $inventoryRepository;
        $this->replenishInventoryCommandHandler = $replenishInventoryCommandHandler;  // Inyectamos el handler de compras
    }
    /**
    * Listen to verify ingredients stock 
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function listen($message)
    {
            $data = json_decode($message->body, true);

            $orderId = $data['order_id'];
            $dishName = $data['dish'];
            $ingredients = $data['ingredients'];

            Log::info("Inventory request received for dish {$dishName}, Order ID: {$orderId}");

            // Verify if all ingrdients are available
            $missingIngredients = [];
            $availableIngredients = [];
            foreach ($ingredients as $ingredient) {
                $ingredientName = $ingredient['name'];
                $requiredQuantity = $ingredient['quantity'];

                $inventoryIngredient = $this->inventoryRepository->findByName($ingredientName);

                // Verify if the required quantity is avaiable
                if (!$inventoryIngredient || $inventoryIngredient->availableQuantity < $requiredQuantity) {
                    $missingIngredients[] = [
                        'name' => $ingredientName,
                        'required_quantity' => $requiredQuantity,
                        'available_quantity' => $inventoryIngredient ? $inventoryIngredient->availableQuantity : 0
                    ];
                }else{
                    $availableIngredients[] = $ingredient;
                }
            }

            // If there are missing igredients buy
            if (!empty($missingIngredients)) {
                Log::info("Dispatch available ingredients for order {$orderId}: " . json_encode($availableIngredients));
                $this->dispatchIngredients($availableIngredients);
                Log::info("Missing ingredients for order {$orderId}: " . json_encode($missingIngredients));
                $ingredientsNeeded = [];
                foreach ($missingIngredients as $missingIngredient) {
                    // Aquí enviamos una solicitud de compra para cada ingrediente faltante
                    $ingredientName = $missingIngredient['name'];
                    $quantityNeeded = $missingIngredient['required_quantity'] - $missingIngredient['available_quantity'];

                    Log::info("Requesting purchase of {$quantityNeeded} units of {$ingredientName} for order {$orderId}");
                    array_push($ingredientsNeeded, [
                        'name' => $ingredientName,
                        'required_quantity' => $quantityNeeded,
                        'total_quantity' => $missingIngredient['required_quantity']
                    ]);
               
                }
                // Send to command handler to purchase missing ingredients 
                $command = new ReplenishInventoryCommand($ingredientsNeeded, $orderId);
                $this->replenishInventoryCommandHandler->handle($command);

                $responseMessage = [
                    'order_id' => $orderId,
                    'status' => 'missing_ingredients',
                    'missing_ingredients' => $missingIngredients,
                    'action' => 'replenishment_in_progress'  
                ];

                Log::info('Sending message to message broker inventory_response_queue');
                Amqp::publish('routing-key-2', json_encode($responseMessage), [
                    'queue' => 'inventory_response_queue',
                    'exchange' => 'exchange_responses', 
                    'exchange_type' => 'direct'
                ]);
            } else {
                // If all ingredients are available, send success response
                $responseMessage = [
                    'order_id' => $orderId,
                    'status' => 'ingredients_available'
                ];
                 Log::info("All ingredients are available for order {$orderId}.");

                // Dispatch ingredients
                $this->dispatchIngredients($ingredients);

                Log::info('Sending message to message broker inventory_response_queue');
                Amqp::publish('routing-key-2', json_encode($responseMessage), [
                    'queue' => 'inventory_response_queue',
                    'exchange' => 'exchange_responses',  
                    'exchange_type' => 'direct'
                ]);
            }
     }

    /**
    * Dispatch ingredients from inventory 
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
     private function dispatchIngredients($ingredients)
     {
         foreach ($ingredients as $ingredient) {
            $ingredientName = $ingredient['name'];
            $requiredQuantity = $ingredient['quantity'];
            $inventoryIngredient = $this->inventoryRepository->findByName($ingredientName);
            $inventoryIngredient->decreaseQuantity($requiredQuantity);
            $this->inventoryRepository->updateQuantity($inventoryIngredient);

            // Log the inventory movement with type 'usage'
            $this->inventoryRepository->logInventoryMovement($inventoryIngredient->id, -$requiredQuantity, InventoryStatus::USAGE->value);

            Log::info("{$requiredQuantity} {$ingredientName} dispatched successfully");
         }
     }
}
