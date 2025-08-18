<?php
use App\Purchases\Domain\Services\PurchaseService;
use App\Purchases\Domain\Repositories\PurchaseRepositoryInterface;
use App\Purchases\Domain\CircuitBreakers\MarketCircuitBreaker;
use App\Purchases\Domain\Strategies\PurchaseStrategy;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

/**
 * Test the PurchaseService class
 */
it('initiates a purchase and logs the result', function () {
    $mockPurchaseRepository = Mockery::mock(PurchaseRepositoryInterface::class);

    $mockCircuitBreaker = Mockery::mock(MarketCircuitBreaker::class);
    
    $mockStrategy = Mockery::mock(PurchaseStrategy::class);

    // Simulate the purchase result
    $purchaseResult = [
        'quantityReceived' => 10,
        'marketResponse' => 'Success',
        'retries' => 0
    ];

    // Configure the strategy mock to return the purchase result
    $mockStrategy->shouldReceive('purchase')
                 ->with('Tomato', 10)
                 ->once()
                 ->andReturn($purchaseResult);

    // Configure the circuit breaker mock to execute the purchase
    $mockCircuitBreaker->shouldReceive('execute')
                       ->andReturnUsing(function ($callback) {
                           return $callback();
                       });

    Log::spy();

    // Configure the repository mock to save the purchase
    $mockPurchaseRepository->shouldReceive('savePurchase')->once();
    $mockPurchaseRepository->shouldReceive('saveMarketTransaction')->once();

    // PurchaseService constructor expects a PurchaseRepositoryInterface and a MarketCircuitBreaker
    $purchaseService = new PurchaseService($mockPurchaseRepository, $mockCircuitBreaker);

    // Execute the `initiatePurchase` method
    $purchaseService->initiatePurchase($mockStrategy, 'Tomato', 10);

    Log::shouldHaveReceived('info')->once();
});

it('retrieves all purchases paginated', function () {
    // Purchase repository mock
    $mockPurchaseRepository = Mockery::mock(PurchaseRepositoryInterface::class);
    
    // Simulate a set of purchases
    $purchases = [
        (object) ['id' => 1, 'ingredient' => 'Tomato', 'quantityRequested' => 10, 'quantityReceived' => 10],
        (object) ['id' => 2, 'ingredient' => 'Onion', 'quantityRequested' => 5, 'quantityReceived' => 3]
    ];

    // Simulate a LengthAwarePaginator instance
    $paginator = new LengthAwarePaginator($purchases, 2, 10);

    // Configure the mock to return the paginated data
    $mockPurchaseRepository->shouldReceive('getAllPurchases')
        ->with(10)
        ->once()
        ->andReturn($paginator);

    // Instance of the PurchaseService service
    $purchaseService = new PurchaseService($mockPurchaseRepository, Mockery::mock(MarketCircuitBreaker::class));

    // Call the `getAllPurchases` method and verify the result
    $result = $purchaseService->getAllPurchases(10);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($result->total())->toBe(2);
});