<?php

namespace App\Purchases\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event that represents the completion of the purchase process
 * Event PurchaseCompleted
 * @package App\Purchases\Domain\Events
 */
class PurchaseCompleted
{
    use Dispatchable, SerializesModels;

    public $purchaseResult;

    public function __construct(array $purchaseResult)
    {
        $this->purchaseResult = $purchaseResult;
    }
}
