<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BenefitResource\Pages;
use App\Filament\Resources\BenefitResource\RelationManagers;
use App\Models\Benefit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BenefitResource extends Resource
{
    protected static ?string $model = Benefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('description')->required()->maxLength(255),
                Forms\Components\FileUpload::make('icon')->image()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\ImageColumn::make('icon')->circular(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBenefits::route('/'),
            'create' => Pages\CreateBenefit::route('/create'),
            'edit' => Pages\EditBenefit::route('/{record}/edit'),
        ];
    }
}
