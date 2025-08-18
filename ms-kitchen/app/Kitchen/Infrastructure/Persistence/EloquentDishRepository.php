<?php


namespace App\Kitchen\Infrastructure\Persistence;

use App\Kitchen\Domain\Entities\Dish;
use App\Kitchen\Domain\Repositories\DishRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Implementation of the DishRepositoryInterface using Eloquent
 * Class EloquentDishRepository
 * @package App\Kitchen\Infrastructure\Persistence
 */
class EloquentDishRepository implements DishRepositoryInterface
{
    public function getRandomDish(): Dish
    {
        return Dish::with('ingredients')->inRandomOrder()->first();
    }
    public function getDishById(int $id): Dish
    {
        return Dish::with('ingredients')->find($id);
    }
    public function getDishByOrderId(int $id): Dish
    {
        return Dish::whereHas('orders', function ($query) use ($id) {
            $query->where('id', $id);
        })->with('ingredients')->first();
    }
    public function getRecipesPaginated($perPage): LengthAwarePaginator
    {
        $dishes = Dish::with('ingredients') 
            ->withCount('ingredients') 
            ->paginate($perPage); 
    
        return $dishes;
    }
}
