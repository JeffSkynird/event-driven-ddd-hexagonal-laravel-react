<?php

namespace App\Kitchen\Domain\Enums;

/**
 * Enums to represent the status of the message queue
 * Enum MessageQueueStatus
 * @package App\Kitchen\Domain\Enums
 */
enum MessageQueueStatus: string
{
    case INGREDIENTS_AVAILABLE = 'ingredients_available';
    case ERROR = 'error';
    case PREPARING = 'preparing';
    case COMPLETED = 'completed';
    case MISSING_INGREDIENTS = 'missing_ingredients';
}
