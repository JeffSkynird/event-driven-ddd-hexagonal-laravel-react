<?php

use App\Kitchen\Domain\Services\DishFactory;
use App\Kitchen\Domain\Repositories\DishRepositoryInterface;
use App\Kitchen\Domain\Entities\Dish;
use Illuminate\Support\Facades\Log;
use Mockery;

/**
 * Test the DishFactory class
 */
it('creates a random dish successfully', function () {
    // Mock of the repository
    $mockRepository = Mockery::mock(DishRepositoryInterface::class);
    
    // Creating a mock of the Dish entity
    $dish = Mockery::mock(Dish::class);

    // Configuring the mock to return a Dish when getRandomDish is called
    $mockRepository->shouldReceive('getRandomDish')->once()->andReturn($dish);

    // Starting the service with the mock
    $dishFactory = new DishFactory($mockRepository);

    // Execute the method and verify that it returns the Dish
    $result = $dishFactory->createRandonDish();

    expect($result)->toBe($dish);
});

it('throws exception if no dish is available', function () {
    // Repository mock that throws an exception instead of returning null
    $mockRepository = Mockery::mock(DishRepositoryInterface::class);

    // Simulation of a behavior where the repository fails directly
    $mockRepository->shouldReceive('getRandomDish')
        ->once()
        ->andThrow(new Exception('There are no dishes available'));

    $dishFactory = new DishFactory($mockRepository);

    // Verify that an exception is thrown when there are no dishes available
    expect(fn() => $dishFactory->createRandonDish())->toThrow(Exception::class, 'There are no dishes available');
});


it('retrieves a dish by order ID successfully', function () {
    // Repository mock
    $mockRepository = Mockery::mock(DishRepositoryInterface::class);

    // Creating a mock of the Dish entity
    $dish = Mockery::mock(Dish::class);

    // Configuring the mock to return a Dish when getDishByOrderId is called
    $mockRepository->shouldReceive('getDishByOrderId')
                   ->with(1) 
                   ->once()
                   ->andReturn($dish);

    // Starting the service with the mock
    $dishFactory = new DishFactory($mockRepository);

    // Execute the method and verify that it returns the Dish
    $result = $dishFactory->getDishByOrderId(1);

    // Verify that the result is the Dish instance we expect
    expect($result)->toBe($dish);
});

it('throws exception when dish is not found by order ID', function () {
    // Repository mock
    $mockRepository = Mockery::mock(DishRepositoryInterface::class);
    $mockRepository->shouldReceive('getDishByOrderId')
                   ->with(1)
                   ->once()
                   ->andReturn(null);

    Log::spy();

    $dishFactory = new DishFactory($mockRepository);

    // Verify that an exception is thrown when the Dish is not found
    expect(fn() => $dishFactory->getDishByOrderId(1))->toThrow(Exception::class, 'Dish not found');

    // Verify that an error log was recorded
    Log::shouldHaveReceived('error')->with('Dish not found');
});
