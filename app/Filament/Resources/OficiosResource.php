<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OficiosResource\Pages;
use App\Filament\Resources\OficiosResource\RelationManagers;
use App\Models\Oficios;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OficiosResource extends Resource
{
    protected static ?string $model = Oficios::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns([
                    'sm' => 3,
                ])
                ->schema([
                    TextInput::make('num_oficio')->required(),
                    Select::make('tipo')
                        ->options([
                            'recibido' => 'Recibido',
                            'enviado' => 'Enviado',
                        ])
                        ->required(),
                    Select::make('estatus')
                        ->options([
                            'sin asignar' => 'Sin asignar',
                            'asignado' => 'Asignado',
                            'concluido' => 'Concluido',
                        ])
                        ->required(),
                ]),
            Section::make('Fechas')
                ->columns([
                    'sm' => 3,
                ])
                ->schema([
                    DatePicker::make('fecha_oficio')->required(),
                    DatePicker::make('fecha_registro')->readOnly()->default(now())->required(),
                    DatePicker::make('fecha_vencimiento')
                        ->default(now()->addDays(7))
                        ->required(),
                ]),

            Select::make('envia_id')
                ->relationship(name: 'envia', modifyQueryUsing: fn($query) => $query->select('id', 'codigo', 'nombre', 'dependencia'))
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->codigo} - {$record->nombre} - ({$record->dependencia})")
                ->searchable()->preload()->nullable()->required(),

            Select::make('turna_id')->label('Turna a')->relationship(name: 'recibe', modifyQueryUsing: fn($query) => $query->select('id', 'codigo', 'nombre', 'dependencia'))->getOptionLabelFromRecordUsing(fn($record) => "{$record->codigo} - {$record->nombre} - ({$record->dependencia})")->searchable()->preload()->nullable()->required(),

            Textarea::make('asunto')->autosize()->required(),
            Textarea::make('observaciones')->autosize(),
            TextInput::make('archivado'),

            FileUpload::make('archivo')
                ->openable()
                ->downloadable()
                ->acceptedFileTypes(['application/pdf'])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('num_oficio')->label('Núm. Oficio')->searchable(),
                TextColumn::make('envia.nombre')->searchable(),
                TextColumn::make('recibe.nombre')->label('Turna a')->searchable(),
                TextColumn::make('fecha_registro'),
                TextColumn::make('estatus')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'sin asignar' => 'Sin asignar',
                        'asignado' => 'Asignado',
                        'concluido' => 'Concluido',
                        default => $state,
                    })->colors([
                        'danger' => 'sin asignar',
                        'primary' => 'asignado',
                        'success' => 'concluido',
                    ])->badge()->searchable(),
            ])

            ->filters([
                SelectFilter::make('estatus')
                    ->label('Filtrar por estatus')
                    ->options([
                        'sin asignar' => 'Sin asignar',
                        'asignado' => 'Asignado',
                        'concluido' => 'Concluido',
                    ]),
                SelectFilter::make('enviar_id')
                    ->label('Filtrar por enviado')
                    ->relationship(
                        'envia', // nombre del método de relación en tu modelo
                        'nombre', // campo base (aunque luego lo sobreescribimos con getOptionLabelFromRecordUsing)
                        modifyQueryUsing: fn($query) => $query->select('id', 'codigo', 'nombre', 'dependencia')
                    )
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->codigo} - {$record->nombre} - ({$record->dependencia})")
                    ->searchable()
            ])
            ->actions([Tables\Actions\EditAction::make(),ViewAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
            'index' => Pages\ListOficios::route('/'),
            'create' => Pages\CreateOficios::route('/create'),
            'edit' => Pages\EditOficios::route('/{record}/edit'),
            'view' => Pages\ViewOficios::route('/{record}'),
        ];
    }
}
