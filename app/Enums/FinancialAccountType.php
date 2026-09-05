<?php

namespace App\Enums;

enum FinancialAccountType: string
{
    case CASH = 'cash';
    case BANK = 'bank';
    case DIGITAL_WALLET = 'digital_wallet';
    case OTHER = 'other';
}
