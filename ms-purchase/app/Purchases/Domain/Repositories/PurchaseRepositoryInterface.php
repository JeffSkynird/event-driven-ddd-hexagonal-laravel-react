<?php

namespace App\Purchases\Domain\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface to define the methods that the PurchaseRepository must implement
 * Interface PurchaseRepositoryInterface
 * @package App\Purchases\Domain\Repositories
 */
interface PurchaseRepositoryInterface
{
    public function savePurchase(array $purchaseData): void;
    public function saveMarketTransaction(array $transactionData): void;
    public function getAllPurchases($perPage): LengthAwarePaginator;
    public function getPurchaseById(int $id): ?array;
}
