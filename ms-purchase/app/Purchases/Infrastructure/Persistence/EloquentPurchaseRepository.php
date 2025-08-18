<?php

namespace App\Purchases\Infrastructure\Persistence;

use App\Purchases\Domain\Repositories\PurchaseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Implementation of the PurchaseRepositoryInterface
 * Class EloquentPurchaseRepository
 * @package App\Purchases\Infrastructure\Persistence
 */
class EloquentPurchaseRepository implements PurchaseRepositoryInterface
{
    public function savePurchase(array $purchaseData): void
    {
        DB::table('purchases')->insert([
            'ingredient_name' => $purchaseData['ingredient'],
            'quantity_purchased' => $purchaseData['quantityReceived'],
            'quantity_requested' => $purchaseData['quantityRequested'],
            'status' => 'completed', // En este caso, está completada si llega aquí
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function saveMarketTransaction(array $transactionData): void
    {
        DB::table('market_transactions')->insert([
            'ingredient_name' => $transactionData['ingredient'],
            'quantity_requested' => $transactionData['quantityRequested'],
            'quantity_received' => $transactionData['quantityReceived'],
            'market_response' => json_encode($transactionData['marketResponse']),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function getAllPurchases($perPage): LengthAwarePaginator
    {
        return DB::table('purchases')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function getPurchaseById(int $id): ?array
    {
        $purchase = DB::table('purchases')->find($id);
        return $purchase ? (array) $purchase : null;
    }
}
