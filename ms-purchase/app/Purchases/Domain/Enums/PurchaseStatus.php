<?php

namespace App\Purchases\Domain\Enums;

/**
 * Enums to represent the status of the purchase process
 * Enum PurchaseStatus
 * @package App\Purchases\Domain\Enums
 */
enum PurchaseStatus: string
{
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
