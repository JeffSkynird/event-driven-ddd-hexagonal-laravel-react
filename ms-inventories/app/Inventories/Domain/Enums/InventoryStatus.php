<?php

namespace App\Inventories\Domain\Enums;

/**
 * Enums to represent the status of the  inventory
 * Enum MessageQueueStatus
 * @package App\Inventories\Domain\Enums
 */
enum InventoryStatus: string
{
    case PURCHASED = 'purchased';
    case USAGE = 'usage';
}