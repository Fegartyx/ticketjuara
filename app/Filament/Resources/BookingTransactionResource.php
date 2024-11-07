<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Models\BookingTransaction;
use App\Models\Ticket;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Customer Management";

    public static function getNavigationBadge(): ?string
    {
        return BookingTransaction::query()->where('is_paid', false)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Product and Price')
                    ->schema([
                        Forms\Components\Select::make('ticket_id')
                        ->relationship('ticket', 'name') // reactive itu aktif ketika user sudah memilih ticket
                        ->searchable()->preload()->required()->reactive()->afterStateUpdated(function ($state, callable $set) {
                            //TODO: ketika user memilih ticket, maka akan mengambil harga dari ticket yang dipilih, lalu menyimpannya ke variabel price pada method set
                            $ticket = Ticket::query()->find($state);
                            $set('price', $ticket ? $ticket->price : 0);
                            }),

                        Forms\Components\TextInput::make('total_participant')->required()->numeric()->prefix('People')->reactive()->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $price = $get('price');
                            $subTotal = $price * $state;
                            $totalPPn = $subTotal * 0.11;
                            $totalAmount = $subTotal + $totalPPn;
                            $set('total_amount', $totalAmount);
                        }),

                        Forms\Components\TextInput::make('total_amount')->required()->prefix('Rp')->readOnly()->helperText("Harga Sudah Include PPN 11%"),
                    ]),

                    Forms\Components\Wizard\Step::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('phone_number')->required()->tel()->maxLength(255),
                        Forms\Components\TextInput::make('email')->required()->email()->maxLength(255),
                        Forms\Components\TextInput::make('booking_trx_id')->required()->maxLength(255),
                    ]),

                    Forms\Components\Wizard\Step::make('is_paid')->schema([
                        Forms\Components\ToggleButtons::make('is_paid')->label('Apakah Sudah Membayar?')->boolean()->grouped()->icons([
                            true => 'heroicon-o-pencil',
                            false => 'heroicon-o-clock',
                        ])->required(),

                        Forms\Components\FileUpload::make('proof')->image()->required(),

                        Forms\Components\DatePicker::make('started_at')->required(),
                    ])
                ])
                ->columnSpanFull()
                ->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('ticket.thumbnail')->label('Thumbnail')->circular(),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('phone_number')->label('Phone Number'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('booking_trx_id')->label('Booking Transaction ID')->searchable(),
                Tables\Columns\IconColumn::make('is_paid')->label('Payment Status')->boolean()->trueColor('success')->falseColor('danger')->trueIcon('heroicon-o-check-circle')->falseIcon('heroicon-o-clock'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ticket_id')
                    ->relationship('ticket', 'name')
                    ->label('Ticket'),
                Tables\Filters\SelectFilter::make('is_paid')
                    ->options([
                        'true' => 'Paid',
                        'false' => 'Unpaid',
                    ])
                    ->label('Payment Status'),
                Tables\Filters\SelectFilter::make('started_at')
                    ->options([
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        'this_week' => 'This Week',
                        'last_week' => 'Last Week',
                        'this_month' => 'This Month',
                        'last_month' => 'Last Month',
                    ])
                    ->label('Date'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->action(function (BookingTransaction $record) {
                    $record->update([
                        'is_paid' => true,
                    ]);

                    Notification::make()
                        ->title('Payment Approved')
                        ->success()
                        ->body('Payment has been approved')
                        ->send();
                })
                ->color('success')
                ->requiresConfirmation()
                ->visible(function (BookingTransaction $record) {
                    return !$record->is_paid;
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->columnToggleFormWidth('auto');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
