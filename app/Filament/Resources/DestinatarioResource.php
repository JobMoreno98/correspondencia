<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinatarioResource\Pages;
use App\Filament\Resources\DestinatarioResource\RelationManagers;
use App\Models\Destinatario;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DestinatarioResource extends Resource
{
    protected static ?string $model = Destinatario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('codigo')->numeric()->maxLength(9)->required(),
                TextInput::make('nombre')->required(),
                TextInput::make('dependencia')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')->numeric()->sortable(),
                TextColumn::make('nombre')->sortable(),
                TextColumn::make('dependencia')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDestinatarios::route('/'),
            'create' => Pages\CreateDestinatario::route('/create'),
            'edit' => Pages\EditDestinatario::route('/{record}/edit'),
        ];
    }
}
