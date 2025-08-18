<?php

namespace App\Kitchen\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $table = 'ingredients';
    protected $fillable = ['name', 'stock'];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'recipes')->withPivot('quantity');
    }
    public function decreaseStock(int $quantity)
    {
        if ($this->stock >=  $quantity) {
            $this->stock -= $quantity;
            $this->save();
        }else {
            throw new \Exception("Insufficient stock for {$this->name}");
        }
    }
   public function recipes()
   {
       return $this->belongsToMany(Recipe::class, 'recipes', 'ingredient_id', 'dish_id')
                   ->withPivot('quantity'); 
   }
}
