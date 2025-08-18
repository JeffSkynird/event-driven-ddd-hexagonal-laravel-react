<?php

namespace App\Kitchen\Domain\Services;

use App\Kitchen\Domain\Entities\Dish;
use App\Kitchen\Domain\Repositories\DishRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class to create dishes with factory method
 * Class DishFactory
 * @package App\Kitchen\Domain\Services
 */
class DishFactory
{
    private DishRepositoryInterface $dishRepository;

    public function __construct(DishRepositoryInterface $dishRepository)
    {
        $this->dishRepository = $dishRepository;
    }

    /**
    * Create a random dish based on the recipes stored
    * @return Dish
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function createRandonDish(): Dish
    {
        // Get a random dish from the database
        $dish = $this->dishRepository->getRandomDish();

        if (!$dish) {
            throw new Exception('There are no dishes available');
        }
        // The dish comes with the associated ingredients (loaded in the repository)
        return $dish;
    }

    /**
    * Get a dish based on its order ID
    * @return Dish
    * @author Jefferson Leon <jeffersonleon12@gmail.com>
    * @version 1.0.0 - 2024-9-22
    */
    public function getDishByOrderId(int $id): Dish
    {
        // Get a dish by ID from the database
        $dish = $this->dishRepository->getDishByOrderId($id);

        if (!$dish) {
            Log::error("Dish not found");
            throw new Exception('Dish not found');
        }
        return $dish;
    }
}
