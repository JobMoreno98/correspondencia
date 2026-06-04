<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\ChunkFileUpload;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\OficiosResource\Pages\ListOficios;
use App\Filament\Resources\OficiosResource\Pages\CreateOficios;
use App\Filament\Resources\OficiosResource\Pages\EditOficios;
use App\Filament\Resources\OficiosResource\Pages\ViewOficios;
use App\Models\Oficios;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use Illuminate\Support\Facades\Gate;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class OficiosResource extends Resource
{
    protected static ?string $model = Oficios::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('num_oficio')->required()->label('Núm. Oficio'),
                        /*
                    Select::make('estatus')
                        ->options([
                            'sin asignar' => 'Sin asignar',
                            'asignado'    => 'Asignado',
                            'concluido'   => 'Concluido',
                        ])
                        ->hidden()
                        ->default('sin asignar')
                        ->required(),*/
                        DatePicker::make('fecha_oficio')->required(),
                        DatePicker::make('fecha_registro')->readOnly()->default(now())->required(),
                    ])->columnSpanFull()->columns(3),
                Select::make('envia_id')
                    ->relationship(name: 'envia', modifyQueryUsing: fn($query) => $query->select('id',  'nombre', 'dependencia')->where('red_udeg', true)->orderBy('nombre'))
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nombre} - ({$record->dependencia})")
                    ->searchable()->preload()->nullable()->required(),

                Select::make('turna_id')->label('Turna a')
                    ->relationship(name: 'recibe', modifyQueryUsing: fn($query) => $query->select('id', 'nombre', 'dependencia')->where('red_udeg', false)->orderBy('nombre'))
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nombre} - ({$record->dependencia})")
                    ->searchable()->preload()->nullable()->required(),

                Textarea::make('asunto')->autosize()->required()->columnSpanFull(),
                Textarea::make('observaciones')->autosize(),
                TextInput::make('archivado'),


                ChunkFileUpload::make('archivo')
                    ->label('Subir documento')->extraAttributes(function ($record) {
                        // Si el registro no existe (es modo creación), permitimos limpiar el input
                        if (! $record) {
                            return ['canDeleteFile' => true];
                        }

                        // En modo edición, verificamos la política de Shield para este registro
                        return [
                            'canDeleteFile' => Gate::allows('delete', $record)
                        ];
                    }),
                /*
                FileUpload::make('archivo')
                    ->openable()->maxSize(102400)
                    ->downloadable()
                    ->acceptedFileTypes(['application/pdf']),
                    

                UppyUpload::make('archivo')
                    ->acceptedFileTypes(['application/pdf'])
                    ->chunkSize(3 * 1024 * 1024)
                    ->disk('public')
                    ->directory('oficios')
                    ->webcam(false)
                    ->audio(false)
                    ->theme('auto')
                    ->dragDrop(true)
                    ->nullable()
                    ->afterStateUpdated(function ($state, callable $set) {
                        logger()->info('STATE', [
                            'state' => $state
                        ]);

                        if (empty($state)) {
                            $set('archivo', null);
                        }
                    })->autoOpenFileEditor()   */
                //->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('num_oficio')->label('Núm. Oficio')->searchable()->sortable(),
                TextColumn::make('envia.nombre')->searchable()->wrap()->sortable(),
                TextColumn::make('recibe.nombre')->label('Turna a')->searchable()->wrap(),
                TextColumn::make('fecha_registro')->date('d-m-yy')->sortable(),
                TextColumn::make('asunto')->wrap()->limit(100)->searchable()
                /*
                TextColumn::make('estatus')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'sin asignar'                 => 'Sin asignar',
                        'asignado'                    => 'Asignado',
                        'concluido'                   => 'Concluido',
                        default                       => $state,
                    })
                    ->colors([
                        'danger'  => 'sin asignar',
                        'primary' => 'asignado',
                        'success' => 'concluido',
                    ])
                    ->badge()
                    ->searchable(),*/
            ])
            ->filters([
                /*
                SelectFilter::make('estatus')
                    ->label('Filtrar por estatus')
                    ->options([
                        'sin asignar' => 'Sin asignar',
                        'asignado'    => 'Asignado',
                        'concluido'   => 'Concluido',
                    ]),*/
                SelectFilter::make('envia_id')
                    ->label('Filtrar por enviado')
                    ->relationship('envia', 'nombre')->preload()
                    ->searchable(),

                Filter::make('fecha_registro_rango')
                    ->label('Rango de Fechas')
                    ->schema([
                        DatePicker::make('desde')->label('Fecha Oficio Desde'),
                        DatePicker::make('hasta')->label('Fecha Oficio Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn($q) => $q->whereDate('fecha_registro', '>=', $data['desde']))
                            ->when($data['hasta'], fn($q) => $q->whereDate('fecha_registro', '<=', $data['hasta']));
                    }),
                Filter::make('registro_exacto')
                    ->label('Fecha Exacta')
                    ->form([
                        DatePicker::make('fecha')->label('Fecha del Registro'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['fecha'], fn($q) => $q->whereDate('fecha_registro', $data['fecha']));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                /*
                ExportBulkAction::make()
                    ->exports([

                        (new OficiosExport())
                            ->withColumns([
                                Column::make('id')->heading('ID'),
                                Column::make('num_oficio')->heading('Número de Oficio'),
                                Column::make('fecha_oficio')->heading('Fecha del Oficio'),
                                Column::make('fecha_registro')->heading('Fecha de Registro'),
                                Column::make('envia.nombre')->heading('Envia'),
                                Column::make('recibe.nombre')->heading('Turna a'),
                                Column::make('asunto')->heading('Asunto'),
                                Column::make('observaciones')->heading('Observaciones'),
                                Column::make('archivado')->heading('Archivado'),
                                Column::make('estatus')->heading('Estatus'),
                            ]),
                    ]),*/
                ExportBulkAction::make()->exports([
                    ExcelExport::make('form')->withColumns([
                        Column::make('id')->heading('ID'),
                        Column::make('num_oficio')->heading('Número de Oficio'),
                        Column::make('fecha_oficio')->heading('Fecha del Oficio'),
                        Column::make('fecha_registro')->heading('Fecha de Registro'),
                        Column::make('envia.nombre')->heading('Envia'),
                        Column::make('recibe.nombre')->heading('Turna a'),
                        Column::make('asunto')->heading('Asunto'),
                        Column::make('observaciones')->heading('Observaciones'),
                        Column::make('archivado')->heading('Archivado'),
                        //Column::make('estatus')->heading('Estatus'),
                    ])->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                ])
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
            'index'  => ListOficios::route('/'),
            'create' => CreateOficios::route('/create'),
            'edit'   => EditOficios::route('/{record}/edit'),
            'view'   => ViewOficios::route('/{record}'),
        ];
    }
}
