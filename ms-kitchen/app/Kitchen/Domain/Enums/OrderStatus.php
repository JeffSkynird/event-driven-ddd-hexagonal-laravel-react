<?php

namespace App\Kitchen\Domain\Enums;

/**
 * Enums to represent the status of the order
 * Enum MessageQueueStatus
 * @package App\Kitchen\Domain\Enums
 */
enum OrderStatus: string
{
    case PENDING = 'pending';
    case PREPARING = 'preparing';
    case COMPLETED = 'completed';
    case RESTOCKING = 'restocking';
    case ERROR = 'error';
}
