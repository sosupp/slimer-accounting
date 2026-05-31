<?php

namespace Sosupp\SlimerAccounting\Enums;

use Sosupp\SlimerAccounting\Models\Account;

enum AccountCode: string 
{
    // Assets
    case ASSETS = '1000';
    case CASH_ON_HAND = '1110';
    case BANK_ACCOUNT = '1120';
    case MOBILE_MONEY = '1130';
    case ACCOUNTS_RECEIVABLE = '1200';
    case INVENTORY = '1300';
    case EQUIPMENT = '1510';
    case FURNITURE = '1520';
    
    
    // Liabilities
    case LIABILITIES = '2000';
    case ACCOUNTS_PAYABLE = '2100';
    case LOAN_PAYABLE = '2200';
    case VAT_PAYABLE = '2300';

    // Equity
    case EQUITY = '3000';
    case OWNER_CAPITAL = '3100';
    case RETAINED_EARNINGS = '3200';

    // Income
    case INCOME = '4000';
    case SALES_REVENUE = '4100';
    case SERVICE_REVENUE = '4200';

    // Expenses
    case EXPENSES = '5000';
    case COST_OF_GOODS_SOLD = '5100';
    case SALARIES_EXPENSE = '5200';
    case RENT_EXPENSE = '5300';
    case UTILITIES_EXPENSE = '5400';
    case TRANSPORT_EXPENSE = '5500';
    case SOFTWARE_EXPENSE = '5600';

    public function account(): Account
    {
        return Account::where('code', $this->value)->firstOrFail();
    }

    public function id(): int
    {
        return $this->account()->id;
    }
}