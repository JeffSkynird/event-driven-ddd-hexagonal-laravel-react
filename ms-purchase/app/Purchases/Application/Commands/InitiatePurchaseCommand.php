<?php
namespace App\Purchases\Application\Commands;

/**
 * Command to start the purchase process
 * Class InitiatePurchaseCommand
 * @package App\Purchases\Application\Commands
 */
class InitiatePurchaseCommand
{
    public $missingIngredients;
    public $orderId;

    public function __construct(array $missingIngredients, int $orderId){
        $this->missingIngredients = $missingIngredients;
        $this->orderId = $orderId;
    }
}
