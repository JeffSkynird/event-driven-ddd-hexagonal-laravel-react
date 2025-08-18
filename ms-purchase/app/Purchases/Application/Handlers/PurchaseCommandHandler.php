<?php

namespace App\Purchases\Application\Handlers;

use App\Purchases\Application\Commands\InitiatePurchaseCommand;
use App\Purchases\Domain\Events\PurchaseCompleted;
use App\Purchases\Domain\Services\PurchaseService;
use App\Purchases\Domain\Strategies\DirectPurchaseStrategy;
use Illuminate\Support\Facades\Log;

/**
 * Command Handler to initiate the purchase process
 * Class PurchaseCommandHandler
 * @package App\Purchases\Application\Handlers
 */
class PurchaseCommandHandler
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function handle(InitiatePurchaseCommand $command)
    {
        $missingIngredients = $command->missingIngredients;
        $orderId = $command->orderId;
        // Direct purchase strategy
        $strategy = new DirectPurchaseStrategy();
        $ingredientsBought = [];
        $maxRetries = 3; // Number of retries
        $attempts = 0;
        $success = false;

        // Attempts to purchase the missing ingredients
        while ($attempts < $maxRetries && !$success) {
            try {
                Log::info("Purchase attempt " . ($attempts + 1));

                foreach ($missingIngredients as $ingredient) {
                    // Purchase the ingredient
                    $purchaseResult = $this->purchaseService->initiatePurchase(
                        $strategy,
                        $ingredient['name'],
                        $ingredient['required_quantity']
                    );

                    array_push($ingredientsBought, [
                        'ingredient' => $ingredient['name'],
                        'quantityRequested' => $ingredient['required_quantity'],
                        'quantityReceived' => $purchaseResult['quantityReceived'],
                        'totalQuantity' => $ingredient['total_quantity'],
                    ]);
                }
                $success = true; 
            } catch (\Exception $e) {
                $attempts++;
                if ($e->getMessage() === 'Open circuit. Cannot perform purchase at this time.') {
                } else {
                    Log::error('Purchase Error: ' . $e->getMessage());
                }
            }
        }
        // If th e purchase was successful, emit completed event
        if($success){
            $completePurchase = [
                'order_id' => $orderId,
                'status' => 'purchase_completed',
                'ingredients' => $ingredientsBought
            ];

            Log::info('Purchase completed for order ' . $orderId);
            Log::info('Ingredients bought: ' . json_encode($ingredientsBought));
            event(new PurchaseCompleted($completePurchase));
            
        }
        // If the purchase was not successful, emit error event
        if (!$success) {
            $completePurchase = [
                'order_id' => $orderId,
                'status' => 'purchase_error',
                'ingredients' => []
            ];
            Log::info('Purchase failed for order ' . $orderId);
            event(new PurchaseCompleted($completePurchase));
        }
    }
}
