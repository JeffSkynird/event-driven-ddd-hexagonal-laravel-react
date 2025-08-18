<?php

namespace App\Kitchen\Domain\Repositories;

use App\Kitchen\Domain\Entities\Dish;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface to define the methods that the DishRepository must implement
 * Interface DishRepositoryInterface
 * @package App\Kitchen\Domain\Repositories
 */
interface DishRepositoryInterface
{
    public function getRandomDish(): Dish;
    public function getDishByOrderId(int $id): ?Dish;
    public function getRecipesPaginated($perPage): LengthAwarePaginator;
}
