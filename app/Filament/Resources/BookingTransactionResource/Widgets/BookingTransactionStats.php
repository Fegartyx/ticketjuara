<?php

namespace App\Filament\Resources\BookingTransactionResource\Widgets;

use App\Models\BookingTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingTransactionStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTransactions = BookingTransaction::query()->count();
        $approvedTransactions = BookingTransaction::query()->where('is_paid', true)->count();
        $totalAmount = BookingTransaction::query()->where('is_paid',true)->sum('total_amount');

        return [
            Stat::make('Transactions', $totalTransactions)
                ->description('Total Transactions')
                ->descriptionIcon('grommet-transaction'),

            Stat::make('Approved Transactions', $approvedTransactions)
                ->description('Approved Transactions')
                ->descriptionIcon('hugeicons-validation-approval')->color('success'),

            Stat::make('Amount', 'IDR ' . number_format($totalAmount))
                ->description('Total Amount from Approved Transactions')
                ->descriptionIcon('healthicons-o-money-bag')->color('success'),
        ];
    }
}
