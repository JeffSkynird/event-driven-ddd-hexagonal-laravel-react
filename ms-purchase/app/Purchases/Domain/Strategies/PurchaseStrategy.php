<?php
namespace App\Purchases\Domain\Strategies;

/**
 * Interface to define the methods that the purchase strategies must implement
 * @package App\Purchases\Domain\Strategies
 */
interface PurchaseStrategy
{
    public function purchase(string $ingredientName, int $quantityRequested): array;
}
