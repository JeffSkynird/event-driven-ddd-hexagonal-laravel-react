<?php

namespace App\Inventories\Infrastructure\Persistence;

use App\Inventories\Domain\Entities\Eloquent\IngredientEloquent;
use App\Inventories\Domain\Entities\Ingredient;
use App\Inventories\Domain\Entities\InventoryMovement;
use App\Inventories\Domain\Repositories\InventoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Implementation of the InventoryRepositoryInterface using Eloquent
 * Class InventoryRepository
 * @package App\Kitchen\Infrastructure\Persistence
 */
class InventoryRepository implements InventoryRepositoryInterface
{
    public function findByName(string $name): ?Ingredient
    {
        $ingredient = DB::table('ingredients')->where('name', $name)->first();

        return $ingredient ? new Ingredient(
            $ingredient->id,
            $ingredient->name,
            $ingredient->available_quantity,
            $ingredient->created_at,
            $ingredient->updated_at
        ) : null;
    }

    public function getAllIngredientsPaginated($perPage)
    {
        $ingredients = IngredientEloquent::with(['inventoryMovements' => function ($query) {
            $query->orderBy('created_at', 'desc'); 
        }])->orderBy('id', 'desc')->paginate($perPage);
    
        return $ingredients;
    }
    
    public function updateQuantity(Ingredient $ingredient)
    {
        DB::table('ingredients')
            ->where('id', $ingredient->id)
            ->update([
                'available_quantity' => $ingredient->availableQuantity,
                'updated_at' => now()
            ]);
    }

    public function logInventoryMovement($ingredientId, $quantity, $type)
    {
        DB::table('inventory_movements')->insert([
            'ingredient_id' => $ingredientId,
            'quantity' => $quantity,
            'type' => $type,
            'created_at' => now()
        ]);
    }

    public function getInventoryMovements()
    {
        return DB::table('inventory_movements')->get()->map(function ($movement) {
            return new InventoryMovement(
                $movement->id,
                $movement->ingredient_id,
                $movement->quantity,
                $movement->type,
                $movement->created_at
            );
        });
    }
}
