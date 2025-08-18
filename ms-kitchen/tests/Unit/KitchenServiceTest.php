<?php

use App\Kitchen\Domain\Services\KitchenService;
use App\Kitchen\Domain\Repositories\OrderRepositoryInterface;
use App\Kitchen\Domain\Repositories\DishRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

/**
 * Test the KitchenService class
 */
it('retrieves all orders successfully', function () {
    // Mock of the order repository 
    $mockOrderRepository = Mockery::mock(OrderRepositoryInterface::class);
    
    // Configuring the mock to return a simulated list of orders
    $mockOrderRepository->shouldReceive('getOrders')->once()->andReturn(['order1', 'order2']);

    // Dish repository mock
    $mockDishRepository = Mockery::mock(DishRepositoryInterface::class);

    // Creating the KitchenService instance with the mocks
    $kitchenService = new KitchenService($mockOrderRepository, $mockDishRepository);

    // Executing the method and verifying the result
    $result = $kitchenService->getOrders();

    expect($result)->toBe(['order1', 'order2']);
});

it('retrieves paginated orders successfully', function () {
    // Order repository mock
    $mockOrderRepository = Mockery::mock(OrderRepositoryInterface::class);
    
    // Configuring the mock to return a simulated paginated list of orders
    $mockOrderRepository->shouldReceive('getOrdersPaginated')
                        ->with(10)  // Simulamos que se pide 10 elementos por página
                        ->once()
                        ->andReturn(['paginated_order1', 'paginated_order2']);

    // Dish repository mock
    $mockDishRepository = Mockery::mock(DishRepositoryInterface::class);

    // Creating the KitchenService instance with the mocks
    $kitchenService = new KitchenService($mockOrderRepository, $mockDishRepository);

    // Executing the method and verifying the result
    $result = $kitchenService->getOrdersPaginated(10);

    expect($result)->toBe(['paginated_order1', 'paginated_order2']);
});
it('retrieves paginated recipes successfully', function () {
    // Dish repository mock
    $mockDishRepository = Mockery::mock(DishRepositoryInterface::class);

    // Create a simulated list of recipes
    $recipes = ['recipe1', 'recipe2'];

    // Simulate pagination using LengthAwarePaginator
    $paginator = new LengthAwarePaginator($recipes, count($recipes), 5);

    // Configuring the mock to return the paginator
    $mockDishRepository->shouldReceive('getRecipesPaginated')
                        ->with(5)
                        ->once()
                        ->andReturn($paginator);

    // Order repository mock
    $mockOrderRepository = Mockery::mock(OrderRepositoryInterface::class);

    // Creating the KitchenService instance with the mocks
    $kitchenService = new KitchenService($mockOrderRepository, $mockDishRepository);

    // Executing the method and verifying the result
    $result = $kitchenService->getRecipesPaginated(5);

    expect($result)->toBe($paginator);
});