<?php

namespace App\Kitchen\Application\Commands;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Command to prepare a dish
 * Class PrepareDishCommand
 * @package App\Kitchen\Application\Commands
 */
class PrepareDishCommand implements ShouldQueue
{
    use Queueable;
    public ?int $orderId;

    public function __construct(?int $orderId)
    {
        $this->orderId = $orderId;
    }
}