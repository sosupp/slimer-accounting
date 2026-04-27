<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Sosupp\SlimerAccounting\Models\Account;

class ChartOfAccountsSeeder extends Seeder
{
    public function run()
    {
        // ASSETS
        $assets = $this->createAccount('Assets', '1000', 'asset');

        $cash = $this->createAccount('Cash', '1100', 'asset', $assets);
        $this->createAccount('Cash on Hand', '1110', 'asset', $cash);
        $this->createAccount('Bank Account', '1120', 'asset', $cash);
        $this->createAccount('Mobile Money', '1130', 'asset', $cash);

        $this->createAccount('Accounts Receivable', '1200', 'asset', $assets);
        $this->createAccount('Inventory', '1300', 'asset', $assets);

        $fixed = $this->createAccount('Fixed Assets', '1500', 'asset', $assets);
        $this->createAccount('Equipment', '1510', 'asset', $fixed);
        $this->createAccount('Furniture', '1520', 'asset', $fixed);

        // LIABILITIES
        $liabilities = $this->createAccount('Liabilities', '2000', 'liability');

        $this->createAccount('Accounts Payable', '2100', 'liability', $liabilities);
        $this->createAccount('Loans Payable', '2200', 'liability', $liabilities);

        // EQUITY
        $equity = $this->createAccount('Equity', '3000', 'equity');

        $this->createAccount('Owner Capital', '3100', 'equity', $equity);
        $this->createAccount('Retained Earnings', '3200', 'equity', $equity);

        // INCOME
        $income = $this->createAccount('Income', '4000', 'income');

        $this->createAccount('Sales Revenue', '4100', 'income', $income);
        $this->createAccount('Service Revenue', '4200', 'income', $income);

        // EXPENSES
        $expenses = $this->createAccount('Expenses', '5000', 'expense');

        $this->createAccount('Cost of Goods Sold', '5100', 'expense', $expenses);
        $this->createAccount('Salaries Expense', '5200', 'expense', $expenses);
        $this->createAccount('Rent Expense', '5300', 'expense', $expenses);
        $this->createAccount('Utilities Expense', '5400', 'expense', $expenses);
        $this->createAccount('Transport Expense', '5500', 'expense', $expenses);
    }

    protected function createAccount($name, $code, $type, $parent = null)
    {
        return Account::create([
            'uid' => Str::uuid(),
            'name' => $name,
            'code' => $code,
            'type' => $type,
            'parent_id' => $parent?->id,
            'is_active' => true,
        ]);
    }
}