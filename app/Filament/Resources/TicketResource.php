<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Customer Management";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('details')
                ->schema([
                    Forms\Components\TextInput::make('name')->label('Name')->required()->maxLength(255),
                    Forms\Components\Textarea::make('address')->label('Address')->rows(3)->maxLength(255)->required(),
                    Forms\Components\FileUpload::make('thumbnail')->image()->label('Thumbnail')->required(),
                    Forms\Components\Repeater::make('photos')->relationship('photos')->schema([
                        Forms\Components\FileUpload::make('photo')->image()->label('Photo')->required(),
                    ]),
                ]),

                Forms\Components\Fieldset::make('additional')
                ->schema([
                    Forms\Components\RichEditor::make('about')->label('About')->required(),
                    Forms\Components\TextInput::make('path_video')->label('Path Video')->required()->maxLength(255),
                    Forms\Components\TextInput::make('price')->label('Price')->required()->numeric()->prefix('Rp'),
                    Forms\Components\Select::make('is_popular')->options([
                        true => 'Popular',
                        false => 'Not Popular',
                    ])->required(),
                    Forms\Components\Select::make('category_id')->relationship('category','name')->searchable()->preload()->required(),
                    Forms\Components\Select::make('seller_id')->relationship('seller','name')->searchable()->preload()->required(),
                    Forms\Components\Select::make('haveBenefits')->relationship('haveBenefits','name')->multiple()->preload()->required(),
                    Forms\Components\TimePicker::make('open_time_at')->label('Open Time At')->required(),
                    Forms\Components\TimePicker::make('closed_time_at')->label('Close Time At')->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->searchable(),
                Tables\Columns\ImageColumn::make('thumbnail')->circular(),
                Tables\Columns\IconColumn::make('is_popular')->boolean()->trueColor('success')->falseColor('danger')->trueIcon('heroicon-s-check')->falseIcon('heroicon-s-x')->label('Popular'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')->label('category')->relationship('category','name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
