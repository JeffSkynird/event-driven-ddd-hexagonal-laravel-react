<?php

namespace App\Inventories\Domain\Entities\Eloquent;

use Illuminate\Database\Eloquent\Model;

class InventoryMovementEloquent extends Model
{
    protected $table = 'inventory_movements'; // Nombre de la tabla

    protected $fillable = ['ingredient_id', 'quantity', 'type', 'created_at', 'updated_at'];

    // Relación con Ingredient: un movimiento de inventario pertenece a un ingrediente
    public function ingredient()
    {
        return $this->belongsTo(IngredientEloquent::class, 'ingredient_id');
    }
}
