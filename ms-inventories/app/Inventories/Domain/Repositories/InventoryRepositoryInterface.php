<?php

namespace App\Inventories\Domain\Repositories;

use App\Inventories\Domain\Entities\Ingredient;

/**
 * Interface to define the methods that the InventoryRepository must implement
 * Interface InventoryRepositoryInterface
 * @package App\Kitchen\Domain\Repositories
 */
interface InventoryRepositoryInterface
{
    public function findByName(string $name): ?Ingredient;
    public function getAllIngredientsPaginated($perPage);
    public function updateQuantity(Ingredient $ingredient);
    public function logInventoryMovement($ingredientId, $quantity, $type);
    public function getInventoryMovements();
}
