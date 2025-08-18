<?php

namespace App\Purchases\Domain\Strategies;

/**
 * Wait for stock purchase strategy
 * Class WaitForStockStrategy
 * @package App\Purchases\Domain\Strategies
 */
class WaitForStockStrategy implements PurchaseStrategy
{
    public function purchase(string $ingredientName, int $quantityRequested): array
    {
       // Other logic to purchase the ingredient
       return [];
    }
}
