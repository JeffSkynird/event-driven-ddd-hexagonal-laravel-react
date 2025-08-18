<?php

use App\Inventories\Infrastructure\Persistence\InventoryRepository;
use App\Inventories\Domain\Entities\Ingredient;
use Illuminate\Support\Facades\DB;
use App\Inventories\Domain\Entities\InventoryMovement;
use Mockery;

/**
 * Test the InventoryRepository class
 */
it('retrieves ingredient by name successfully', function () {
    $ingredientData = (object)[
        'id' => 1,
        'name' => 'Tomato',
        'available_quantity' => 50,
        'created_at' => now(),
        'updated_at' => now()
    ];
    DB::shouldReceive('table')
        ->with('ingredients')
        ->andReturnSelf();

    DB::shouldReceive('where')
        ->with('name', 'Tomato')
        ->andReturnSelf(); 

    DB::shouldReceive('first')
        ->once()
        ->andReturn($ingredientData); 

    $repository = new InventoryRepository();

    $ingredient = $repository->findByName('Tomato');

    expect($ingredient)->toBeInstanceOf(Ingredient::class);
    expect($ingredient->name)->toBe('Tomato');
});

it('updates ingredient quantity successfully', function () {
    $ingredient = new Ingredient(1, 'Tomato', 50, now(), now());

    DB::shouldReceive('table')
        ->with('ingredients')
        ->andReturnSelf(); 

    DB::shouldReceive('where')
        ->with('id', $ingredient->id)
        ->andReturnSelf(); 

    DB::shouldReceive('update')
        ->with(Mockery::on(function ($data) use ($ingredient) {
            return $data['available_quantity'] === $ingredient->availableQuantity &&
                   isset($data['updated_at']);
        }))
        ->once()
        ->andReturn(true);

    $repository = new InventoryRepository();

    $repository->updateQuantity($ingredient);

    expect(true)->toBeTrue();
});
it('logs inventory movement successfully', function () {
    DB::shouldReceive('table')
        ->with('inventory_movements')
        ->andReturnSelf(); // Permitir el encadenamiento

    DB::shouldReceive('insert')
        ->with(Mockery::on(function ($data) {
            return $data['ingredient_id'] === 1 &&
                   $data['quantity'] === 10 &&
                   $data['type'] === 'addition' &&
                   isset($data['created_at']);
        }))
        ->once()
        ->andReturn(true);

    $repository = new InventoryRepository();

    $repository->logInventoryMovement(1, 10, 'addition');

    expect(true)->toBeTrue();
});

it('retrieves all inventory movements successfully', function () {
    $movementData = (object)[
        'id' => 1,
        'ingredient_id' => 1,
        'quantity' => 10,
        'type' => 'addition',
        'created_at' => now()
    ];

    DB::shouldReceive('table')
        ->with('inventory_movements')
        ->andReturnSelf(); 

    DB::shouldReceive('get')
        ->once()
        ->andReturn(collect([$movementData]));

    $repository = new InventoryRepository();

    $movements = $repository->getInventoryMovements();

    expect($movements->first())->toBeInstanceOf(InventoryMovement::class);
    expect($movements->first()->quantity)->toBe(10);
});