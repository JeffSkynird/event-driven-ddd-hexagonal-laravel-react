<?php
namespace App\Purchases\Domain\Services;

use App\Purchases\Domain\Strategies\PurchaseStrategy;
use App\Purchases\Domain\Repositories\PurchaseRepositoryInterface;
use App\Purchases\Domain\CircuitBreakers\MarketCircuitBreaker;
use App\Purchases\Domain\Enums\PurchaseStatus;
use Illuminate\Support\Facades\Log;

/**
 * Service class to manage the purchase process
 * Class PurchaseService
 * @package App\Purchases\Domain\Services
 */
class PurchaseService
{
    protected $purchaseRepository;
    protected $circuitBreaker;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository, MarketCircuitBreaker $circuitBreaker)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->circuitBreaker = $circuitBreaker;
    }

    /**
    * Initiates the purchase process
    * @param PurchaseStrategy $strategy, string $ingredientName, int $quantityRequested
    * @return mixed
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function initiatePurchase(PurchaseStrategy $strategy, string $ingredientName, int $quantityRequested)
    {
        return $this->circuitBreaker->execute(function () use ($strategy, $ingredientName, $quantityRequested) {
            // Execute the purchase strategy with retries
            $purchaseResult = $strategy->purchase($ingredientName, $quantityRequested);
            $purchaseStatus = $purchaseResult['quantityReceived'] > 0 ? PurchaseStatus::COMPLETED->value : PurchaseStatus::FAILED->value;
            Log::info("Purchase {$purchaseStatus} for ingredient $ingredientName with quantity ".$purchaseResult['quantityReceived']);

            $this->purchaseRepository->savePurchase([
                'ingredient' => $ingredientName,
                'quantityReceived' => $purchaseResult['quantityReceived'],
                'quantityRequested' => $quantityRequested,
                'status' => $purchaseStatus,
            ]);

            $this->purchaseRepository->saveMarketTransaction([
                'ingredient' => $ingredientName,
                'quantityRequested' => $quantityRequested,
                'quantityReceived' => $purchaseResult['quantityReceived'],
                'marketResponse' => $purchaseResult['marketResponse'],
                'retries' => $purchaseResult['retries']  
            ]);
            return $purchaseResult;
        });
    }

    /**
     * Get all purchases
     * @param $perPage
     * @author Jefferson Leon <jeffersonleon12@gmail.com>
     * @version 1.0.0 - 2024-9-22
     */
    public function getAllPurchases($perPage)
    {
        return $this->purchaseRepository->getAllPurchases($perPage);
    }
}
