<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DestinatarioResource\Pages\ListDestinatarios;
use App\Filament\Resources\DestinatarioResource\Pages\CreateDestinatario;
use App\Filament\Resources\DestinatarioResource\Pages\EditDestinatario;
use App\Filament\Resources\DestinatarioResource\Pages\ViewDestinatario;
use App\Filament\Resources\DestinatarioResource\Pages;
use App\Filament\Resources\DestinatarioResource\RelationManagers;
use App\Models\Destinatario;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DestinatarioResource extends Resource
{
    protected static ?string $model = Destinatario::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')->required(),
            TextInput::make('dependencia')->required()
            //Toggle::make('red_udeg')->label('Externo CUCSH')->default(true)->inline()->onColor('success')->offColor('danger')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nombre')->sortable()->searchable(),
                TextColumn::make('dependencia')->sortable()
            ])->searchable()
            ->filters([
                //
            ])
            ->recordActions([EditAction::make(), ViewAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
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
            'index' => ListDestinatarios::route('/'),
            'create' => CreateDestinatario::route('/create'),
            'edit' => EditDestinatario::route('/{record}/edit'),
            'view' => ViewDestinatario::route('/{record}'),
        ];
    }
}
