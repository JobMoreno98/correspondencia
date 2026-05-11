<?php

namespace App\Filament\Resources;


use App\Filament\Resources\OficiosResource\Pages;
use App\Models\Oficios;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class OficiosResource extends Resource
{
    protected static ?string $model = Oficios::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->openRecordUrlInNewTab()
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 3,
                    ])
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
                    ]),
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

                FileUpload::make('archivo')
                    ->openable()
                    ->downloadable()
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),
            ])        ->headerActions([
            Action::make('export')
                ->label('Exportar PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (array $data) {
                    // lógica para exportar usando tus datos del formulario
                    // por ejemplo llamar a dompdf
                }),

            Action::make('reset')
                ->label('Reiniciar')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn () => session()->flash('message', 'Formulario reiniciado')),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
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
                    ->form([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
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
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

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
            'index'  => Pages\ListOficios::route('/'),
            'create' => Pages\CreateOficios::route('/create'),
            'edit'   => Pages\EditOficios::route('/{record}/edit'),
            'view'   => Pages\ViewOficios::route('/{record}'),
        ];
    }
    
}
