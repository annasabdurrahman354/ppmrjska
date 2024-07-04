<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlotKamarAsramaResource\Pages;
use App\Models\Asrama;
use App\Models\KamarAsrama;
use App\Models\TahunAjaran;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;

class PlotKamarAsramaResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;
    protected static ?string $slug = 'plot-kamar';
    protected static ?string $modelLabel = 'Plot Kamar';
    protected static ?string $pluralModelLabel = 'Plot Kamar';
    protected static ?string $recordTitleAttribute = 'tahun_ajaran';

    protected static ?string $navigationLabel = 'Plot Kamar';
    protected static ?string $navigationGroup = 'Manajemen Administrasi';
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?int $navigationSort = 43;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Cluster::make([
                    TextInput::make('tahun_ajaran_awal')
                        ->hiddenLabel()
                        ->required()
                        ->numeric()
                        ->default(date('Y'))
                        ->live(onBlur: true)
                        ->afterStateHydrated(function (Get $get, Set $set){
                            $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                        }),
                    TextInput::make('tahun_ajaran_akhir')
                        ->hiddenLabel()
                        ->required()
                        ->numeric()
                        ->default(date('Y')+1)
                        ->gt('tahun_ajaran_awal')
                        ->live(onBlur: true)
                        ->afterStateHydrated(function (Get $get, Set $set){
                            $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                        }),
                    ])
                    ->label('Tahun Ajaran')
                    ->columnSpanFull(),

                Hidden::make('tahun_ajaran'),

                Repeater::make('asrama')
                    ->hiddenLabel()
                    ->addable(false)
                    ->deletable(false)
                    ->orderable(false)
                    ->default(Asrama::with(['kamarAsrama'])->get()->map(function ($asrama) {
                        return [
                            'asrama_id' => $asrama->id,
                            'penghuni' => $asrama->penghuni,
                            'lantai' => $asrama->kamarAsrama->groupBy('lantai')->map(function ($kamarPerLantai, $lantai) {
                                return [
                                    'nomor_lantai' => $lantai,
                                    'kamar_asrama' => $kamarPerLantai->map(function ($kamar) {
                                        return [
                                            'kamar_asrama_id' => $kamar->id,
                                            'penghuni' => []
                                        ];
                                    })->toArray(),
                                ];
                            })->values()->toArray(),
                        ];
                    })->toArray())
                    ->schema([
                        Select::make('asrama_id')
                            ->label('Nama Asrama')
                            ->hiddenLabel()
                            ->disabled()
                            ->dehydrated()
                            ->options(Asrama::all()->pluck('nama', 'id')->toArray())
                            ->required()
                            ->columnSpanFull(),
                        Hidden::make('penghuni'),
                        TableRepeater::make('lantai')
                            ->streamlined()
                            ->hiddenLabel()
                            ->columnSpan(1)
                            ->addable(false)
                            ->deletable(false)
                            ->orderable(false)
                            ->headers([
                                Header::make('Lantai')
                                    ->width('12px'),
                                Header::make('Kamar')
                            ])
                            ->schema([
                                TextInput::make('nomor_lantai')
                                    ->label('Nomor Lantai')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpanFull(),
                                TableRepeater::make('kamar_asrama')
                                    ->streamlined()
                                    ->renderHeader(false)
                                    ->columnSpanFull()
                                    ->addable(false)
                                    ->deletable(false)
                                    ->orderable(false)
                                    ->columnSpanFull()
                                    ->headers([
                                        Header::make('Nomor')
                                            ->width('16px'),
                                        Header::make('Penghuni')
                                    ])
                                    ->schema([
                                        Select::make('kamar_asrama_id')
                                            ->label('Nomor Kamar Asrama')
                                            ->disabled()
                                            ->dehydrated()
                                            ->options(fn (Get $get) => KamarAsrama::where('asrama_id', $get('../../../../asrama_id'))->pluck('nomor_kamar', 'id')->toArray())
                                            ->required()
                                            ->columnSpanFull(),
                                        TableRepeater::make('penghuni')
                                            ->streamlined()
                                            ->renderHeader(false)
                                            ->maxItems(fn (Get $get) => Asrama::where('id', $get('../../../../asrama_id'))->first()?->kapasitas_per_kamar ?? 2)
                                            ->headers([
                                                Header::make('Santri'),
                                                //Header::make('Kelas')
                                            ])
                                            ->schema([
                                                Select::make('user_id')
                                                    ->options(fn (Get $get) =>
                                                        User::where('tanggal_lulus_pondok', null)
                                                            ->where('jenis_kelamin', $get('../../../../../../penghuni'))
                                                            ->get()
                                                            ->pluck('nama', 'id')
                                                            ->toArray()
                                                    )
                                                    ->preload()
                                                    ->searchable()
                                                    //->live()
                                                    ->required(),
                                                //Placeholder::make('kelas')
                                                //    ->hiddenLabel()
                                                //    ->content(fn (Get $get) => User::where('id', $get('user_id'))->first()?->kelas ?? '')
                                            ])
                                            ->addActionLabel('+ Penghuni Kamar')
                                    ])

                            ]),
                    ])
                    ->addActionLabel('+ Lantai')
                    ->collapsible()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('users_with_plot')
                    ->label('Santri Terploting')
                    ->getStateUsing(fn ($record) => $record->users_with_plot),
                Tables\Columns\TextColumn::make('users_without_plot')
                    ->label('Santri Belum Terploting')
                    ->getStateUsing(fn ($record) => $record->users_without_plot),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPlotKamarAsrama::class,
            Pages\ManageTerplotingKamarAsrama::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //PlotKamarAsramaRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlotKamarAsramas::route('/'),
            'create' => Pages\CreatePlotKamarAsrama::route('/create'),
            'view' => Pages\ViewPlotKamarAsrama::route('/{record}'),
            'edit' => Pages\EditPlotKamarAsrama::route('/{record}/edit'),
            'terploting' => Pages\ManageTerplotingKamarAsrama::route('/{record}/terploting'),
        ];
    }
}
