<?php 

namespace App\Kitchen\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Kitchen\Domain\Entities\Dish;

class Order extends Model
{
    use HasFactory;
    
    protected $table = 'orders';

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id');
    }
}