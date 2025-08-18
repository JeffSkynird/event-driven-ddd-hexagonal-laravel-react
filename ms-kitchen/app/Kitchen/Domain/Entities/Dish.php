<?php 

namespace App\Kitchen\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;
    protected $table = 'dishes';

    private string $name;
    private array $ingredients;

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipes')->withPivot('quantity');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'dish_id');
    }
}
