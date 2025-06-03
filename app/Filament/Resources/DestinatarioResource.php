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
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DestinatarioResource extends Resource
{
    protected static ?string $model = Destinatario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('codigo')
                ->numeric()
                ->maxLength(9)
                ->required()
                ->inputMode('decimal')
                ->rules(['numeric'])->unique(),
            TextInput::make('nombre')->required(),
            TextInput::make('dependencia')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')->numeric()->sortable()->formatStateUsing(fn ($state) => rtrim(rtrim((string) $state, '0'), '.'))->searchable(), 
                TextColumn::make('nombre')->sortable()->searchable(), 
                TextColumn::make('dependencia')->sortable()])->searchable()
            ->filters([
                //
            ])
            ->actions([Tables\Actions\EditAction::make(),ViewAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
                ExportBulkAction::make()
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
            'view' => Pages\ViewDestinatario::route('/{record}'),
        ];
    }
}
