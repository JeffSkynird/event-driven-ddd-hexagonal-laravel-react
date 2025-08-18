<?php

namespace App\Inventories\Infrastructure\Messaging;

use App\Inventories\Domain\Repositories\InventoryRepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Listener to handle the message from the message broker
 * Class IngredientUsageListener
 * @package App\Inventories\Infrastructure\Messaging
 */
class IngredientUsageListener
{
    protected $inventoryRepository;

    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }
     /**
    * Listen to the message from the message broker listener
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function listen($message, $resolver)
    {
        Log::info("Iniciando listener de uso de ingredientes...");

            Log::info("Mensaje recibido: ingredient_usage_queue - " . $message->body);
            $data = json_decode($message->body, true);

            $orderId = $data['order_id'];
            $ingredients = $data['ingredients'];

            Log::info("Recibida notificación de uso de ingredientes para la orden {$orderId}");

            foreach ($ingredients as $ingredient) {
                $ingredientName = $ingredient['name'];
                $quantityUsed = $ingredient['quantity'];

                // Buscar en el inventario
                $inventoryIngredient = $this->inventoryRepository->findByName($ingredientName);

                if ($inventoryIngredient && $inventoryIngredient->availableQuantity >= $quantityUsed) {
                    // Disminuir la cantidad de stock disponible
                    $inventoryIngredient->decreaseQuantity($quantityUsed);

                    // Actualizar la base de datos
                    $this->inventoryRepository->updateQuantity($inventoryIngredient);

                    // Registrar el movimiento en el inventario con tipo 'usage'
                    $this->inventoryRepository->logInventoryMovement($inventoryIngredient->id, -$quantityUsed, 'usage');
                } else {
                    Log::error("No hay suficiente cantidad de {$ingredientName} para completar la orden {$orderId}");
                }
            }

            $resolver->acknowledge($message);
           
    }
}
