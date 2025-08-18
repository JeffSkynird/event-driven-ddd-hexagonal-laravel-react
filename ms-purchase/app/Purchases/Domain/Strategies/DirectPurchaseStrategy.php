<?php

namespace App\Purchases\Domain\Strategies;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Direct purchase strategy
 * Class DirectPurchaseStrategy
 * @package App\Purchases\Domain\Strategies
 */
class DirectPurchaseStrategy implements PurchaseStrategy
{
    private $retryInterval = 2;  // Interval in seconds between retries

    /**
     * Purchase the specified quantity of the given ingredient
     * @param string $ingredientName
     * @param int $quantityRequested
     * @return array
     */
    public function purchase(string $ingredientName, int $quantityRequested): array
    {
        $totalQuantityReceived = 0;  
        $retries = 0;
        Log::info("Purchasing $quantityRequested units of $ingredientName");
        // Try to purchase until the total quantity requested is received
        while ($totalQuantityReceived < $quantityRequested) {
            // Make the request to the external market
            $response = Http::get(env('URL_MARKETPLACE'), [
                'ingredient' => $ingredientName
            ]);

            // Get the quantity sold in this transaction
            $quantityReceived = $response->json()['quantitySold'] ?? 0;

            // Add the received quantity
            $totalQuantityReceived += $quantityReceived;

            Log::info("Units requested: $quantityRequested, units received: $quantityReceived");
            // If the received quantity is less than the requested, wait and retry
            if ($totalQuantityReceived < $quantityRequested) {
                $retries++;
                sleep($this->retryInterval); 
            }
        }
        return [
            'ingredient' => $ingredientName,
            'quantityRequested' => $quantityRequested,
            'quantityReceived' => $totalQuantityReceived,
            'marketResponse' => $response->json(),
            'retries' => $retries
        ];
    }
}
