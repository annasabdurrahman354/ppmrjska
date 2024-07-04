<?php

namespace App\Filament\Resources;

use App\Enums\JenisKelamin;
use App\Filament\Resources\PendaftaranResource\Pages\CreatePendaftaran;
use App\Filament\Resources\PendaftaranResource\Pages\EditPendaftaran;
use App\Filament\Resources\PendaftaranResource\Pages\ListPendaftarans;
use App\Filament\Resources\PendaftaranResource\Pages\ViewPendaftaran;
use App\Models\Pendaftaran;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;
    protected static ?string $slug = 'pendaftaran';
    protected static ?string $modelLabel = 'Pendaftaran';
    protected static ?string $pluralModelLabel = 'Pendaftaran';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Pendaftaran';
    protected static ?string $navigationGroup = 'Manajemen Pendaftaran';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tahun_pendaftaran')
                    ->label('Tahun Pendaftaran')
                    ->integer()
                    ->minValue(2015),
                TableRepeater::make('kontak_panitia')
                    ->label('Kontak Panitia')
                    ->addActionLabel('+ Tambah Kontak Panitia')
                    ->headers([
                        Header::make('Nama Panitia'),
                        Header::make('Nomor Telepon'),
                        Header::make('Jenis Kelamin'),
                    ])
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Panitia')
                            ->required(),
                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required(),
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options(JenisKelamin::class)
                            ->required(),
                    ])
                    ->minItems(1)
                    ->addable()
                    ->deletable()
                    ->reorderable()
                    ->columnSpanFull(),

                TableRepeater::make('kontak_pengurus')
                    ->label('Kontak Pengurus')
                    ->addActionLabel('+ Tambah Kontak Pengurus')
                    ->headers([
                        Header::make('Nama Panitia'),
                        Header::make('Nomor Telepon'),
                        Header::make('Jenis Kelamin'),
                    ])
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Panitia')
                            ->required(),
                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required(),
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options(JenisKelamin::class)
                            ->required(),
                    ])
                    ->addable()
                    ->deletable()
                    ->reorderable()
                    ->columnSpanFull(),

                TableRepeater::make('indikator_penilaian')
                    ->label('Indikator Penilaian')
                    ->addActionLabel('+ Tambah Indikator Penilaian')
                    ->headers([
                        Header::make('Indikator'),
                    ])
                    ->schema([
                        TextInput::make('indikator')
                            ->label('Indikator')
                            ->helperText('Contoh: Tes Bacaan, Tes Pegon')
                            ->required(),
                    ])
                    ->minItems(1)
                    ->addable()
                    ->deletable()
                    ->reorderable()
                    ->columnSpanFull(),

                TagsInput::make('berkas_pendaftaran')
                    ->label('Berkas Pendaftaran')
                    ->helperText('Contoh: Foto Kartu Keluarga, Foto KTP'),

                Repeater::make('gelombangPendaftaran')
                    ->relationship('gelombangPendaftaran')
                    ->label('Gelombang Pendaftaran')
                    ->addActionLabel('+ Tambah Gelombang Pendaftaran')
                    ->schema([
                            TextInput::make('nomor_gelombang')
                                ->integer()
                                ->required(),
                            TextInput::make('link_grup')
                                ->label('Link Grup')
                                ->url()
                                ->required(),
                            DateTimePicker::make('batas_awal_pendaftaran')
                                ->label('Batas Mulai Pendaftaran')
                                ->beforeOrEqual('batas_akhir_pendaftaran')
                                ->required(),
                            DateTimePicker::make('batas_akhir_pendaftaran')
                                ->label('Batas Akhir Pendaftaran')
                                ->afterOrEqual('batas_awal_pendaftaran')
                                ->required(),
                            TableRepeater::make('timeline')
                                ->label('Timeline')
                                ->addActionLabel('+ Tambah Timeline')
                                ->headers([
                                    Header::make('Rundown'),
                                    Header::make('Tanggal'),
                                ])
                                ->schema([
                                    Select::make('rundown')
                                        ->label('Rundown')
                                        ->placeholder('Cth: Mulai Pendaftaran, Daftar Ulang, Osanru')
                                        ->required(),
                                    DatePicker::make('tanggal')
                                        ->label('Tanggal')
                                        ->required()
                                ])
                                ->minItems(1)
                                ->addable()
                                ->deletable()
                                ->reorderable()
                                ->columnSpanFull(),

                    ])
                    ->columns(2)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_pendaftaran')
                    ->label('Tahun Pendaftaran')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gelombang_pendaftaran_counts')
                    ->label('Jumlah Gelombang Pendaftaran')
                    ->counts('gelombangPendaftaran'),
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
            'index' => ListPendaftarans::route('/'),
            'create' => CreatePendaftaran::route('/create'),
            'view' => ViewPendaftaran::route('/{record}'),
            'edit' => EditPendaftaran::route('/{record}/edit'),
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
