<?php

namespace App\Enums;

enum FinancialMovementType: string
{
    case OPENING = 'opening';
    case INCOME = 'income';
    case EXPENSE = 'expense';
    case TRANSFER_IN = 'transfer_in';
    case TRANSFER_OUT = 'transfer_out';
    case ADJUSTMENT_IN = 'adjustment_in';
    case ADJUSTMENT_OUT = 'adjustment_out';
    case RECEIVABLE_PAYMENT = 'receivable_payment';
    case PAYABLE_PAYMENT = 'payable_payment';
}
