<?php

namespace App\Filament\Resources;

use App\Enums\JenisKelamin;
use App\Enums\KepemilikanGedung;
use App\Enums\PembebananBiayaAsrama;
use App\Filament\Resources\AsramaResource\Pages;
use App\Filament\Resources\AsramaResource\RelationManagers;
use App\Models\Asrama;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;
    protected static ?string $slug = 'asrama';
    protected static ?string $modelLabel = 'Asrama';
    protected static ?string $pluralModelLabel = 'Asrama';

    protected static ?string $navigationLabel = 'Asrama';
    protected static ?string $navigationGroup = 'Manajemen Administrasi';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Umum')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Asrama/Gedung')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\ToggleButtons::make('penghuni')
                            ->label('Penghuni')
                            ->options(JenisKelamin::class)
                            ->inline()
                            ->grouped()
                            ->required(),
                        Forms\Components\TextInput::make('alamat')
                            ->label('Alamat'),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),


                Forms\Components\Section::make('Lokasi')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('foto')
                            ->label('Foto')
                            ->collection('asrama_foto')
                            ->conversion('thumb')
                            ->moveFiles()
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:2')
                            ->optimize('jpg')
                            ->maxSize(1024 * 3)
                            ->rules('dimensions:max_width=1080,max_height=720')
                            ->required()
                            ->columnSpanFull(),
                        Map::make('lokasi')
                            ->label('Lokasi')
                            ->required()
                            ->columnSpanFull()
                            ->afterStateUpdated(function (Get $get, Set $set, string|array|null $old, ?array $state): void {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            })
                            ->afterStateHydrated(function ($state, $record, Set $set): void {
                                if ($record){
                                    $set('lokasi', ['lat' => $record->latitude, 'lng' => $record->longitude]);
                                }
                            })
                            ->extraStyles([
                                'min-height: 100vh',
                                'border-radius: 12px'
                            ])
                            ->liveLocation()
                            ->showMarker()
                            ->markerColor("#22c55eff")
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->zoom(15)
                            ->detectRetina()
                            ->showMyLocationButton()
                            ->extraTileControl([])
                            ->extraControl([
                                'zoomDelta'           => 1,
                                'zoomSnap'            => 2,
                            ]),
                        Forms\Components\TextInput::make('latitude')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Kapasitas Hunian')
                    ->schema([
                        Forms\Components\TextInput::make('kapasitas_per_kamar')
                            ->label('Kapasitas Per Kamar')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0),
                        Forms\Components\TextInput::make('kapasitas_total')
                            ->label('Kapasitas Total')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Kepemilikan')
                    ->schema([
                        Forms\Components\ToggleButtons::make('kepemilikan_gedung')
                            ->label('Kepemilikan Gedung')
                            ->options(KepemilikanGedung::class)
                            ->required()
                            ->inline()
                            ->grouped()
                            ->default(KepemilikanGedung::PPM)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state === KepemilikanGedung::PPM) {
                                    $set('nama_pemilik', 'PPM');
                                }
                            }),
                        Forms\Components\TextInput::make('nama_pemilik')
                            ->label('Nama Pemilik')
                            ->required()
                            ->default('PPM')
                            ->readOnly(fn (Get $get) => $get('kepemilikan_gedung') === KepemilikanGedung::PPM)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nomor_telepon_pemilik')
                            ->label('Nomor Telepon Pemilik')
                            ->tel()
                            ->required()
                            ->maxLength(13)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Biaya dan Tagihan')
                    ->schema([
                        Forms\Components\TextInput::make('biaya_asrama_tahunan')
                            ->label('Biaya Asrama Tahunan')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->suffix(',00')
                            ->required(),
                        Forms\Components\ToggleButtons::make('pembebanan_biaya_asrama')
                            ->label('Pembebanan Biaya Asrama')
                            ->options(PembebananBiayaAsrama::class)
                            ->inline()
                            ->grouped()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Lantai dan Kamar')
                    ->schema([
                        Forms\Components\Repeater::make('lantai')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\TextInput::make('nomor_lantai')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->distinct(),
                                TableRepeater::make('kamar_asrama')
                                    ->label('Kamar Asrama')
                                    ->headers([
                                        Header::make('Nomor Kamar'),
                                        Header::make('Status Ketersediaan')
                                    ])
                                    ->schema([
                                        Forms\Components\Hidden::make('id'),
                                        Forms\Components\TextInput::make('nomor_kamar')
                                            ->label('Nomor Kamar')
                                            ->required()
                                            ->distinct(),
                                        Forms\Components\Checkbox::make('status_ketersediaan')
                                            ->label('Ketersediaan')
                                            ->default(true),
                                    ])
                                    ->addActionLabel('+ Kamar Asrama')
                            ])
                            ->addActionLabel('+ Lantai')
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('penghuni')
                    ->label('Penghuni')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('biaya_asrama_tahunan')
                    ->label('Biaya Asrama Per Tahun')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pembebanan_biaya_asrama')
                    ->label('Pembebanan Biaya Asrama')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kapasitas_per_kamar')
                    ->label('Kapasitas Per Kamar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kapasitas_total')
                    ->label('Kapasitas Total')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kepemilikan_gedung')
                    ->label('Kepemilikan Gedung')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pemilik')
                    ->label('Nama Pemilik')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_telepon_pemilik')
                    ->label('Nomor Telepon Pemilik')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListAsramas::route('/'),
            'create' => Pages\CreateAsrama::route('/create'),
            'view' => Pages\ViewAsrama::route('/{record}'),
            'edit' => Pages\EditAsrama::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
