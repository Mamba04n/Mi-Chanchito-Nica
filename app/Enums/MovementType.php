<?php

namespace App\Enums;

enum MovementType: string
{
    case OPENING = 'opening';
    case IN = 'in';
    case OUT = 'out';
    case ADJUSTMENT_IN = 'adjustment_in';
    case ADJUSTMENT_OUT = 'adjustment_out';
    case TRANSFER_IN = 'transfer_in';
    case TRANSFER_OUT = 'transfer_out';

    public function isPositive(): bool
    {
        return in_array($this, [self::OPENING, self::IN, self::ADJUSTMENT_IN, self::TRANSFER_IN]);
    }

    public function isNegative(): bool
    {
        return in_array($this, [self::OUT, self::ADJUSTMENT_OUT, self::TRANSFER_OUT]);
    }
}
