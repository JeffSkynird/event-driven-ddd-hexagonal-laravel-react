<?php

namespace App\Inventories\Domain\Entities\Eloquent;

use Illuminate\Database\Eloquent\Model;

class IngredientEloquent extends Model
{
    protected $table = 'ingredients'; // Nombre de la tabla
    protected $fillable = ['name', 'availableQuantity', 'minimumThreshold', 'createdAt', 'updatedAt'];

    // Relación con InventoryMovement (un ingrediente tiene muchos movimientos de inventario)
  public function inventoryMovements()
  {
      return $this->hasMany(InventoryMovementEloquent::class, 'ingredient_id');
  }

    // Método para aumentar la cantidad disponible
    public function increaseQuantity($amount)
    {
        $this->availableQuantity += $amount;
    }

    // Método para disminuir la cantidad disponible
    public function decreaseQuantity($amount)
    {
        $this->availableQuantity -= $amount;
    }
}
