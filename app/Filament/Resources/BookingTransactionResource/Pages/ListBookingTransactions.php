<?php

namespace App\Filament\Resources\BookingTransactionResource\Pages;

use App\Filament\Resources\BookingTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingTransactions extends ListRecords
{
    protected static string $resource = BookingTransactionResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            BookingTransactionResource\Widgets\BookingTransactionStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
