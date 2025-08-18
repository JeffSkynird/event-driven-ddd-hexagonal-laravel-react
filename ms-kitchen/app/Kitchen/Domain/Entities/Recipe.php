<?php
namespace App\Kitchen\Domain\Entities;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes'; 

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipes', 'dish_id', 'ingredient_id')
                    ->withPivot('quantity'); 
    }
}
