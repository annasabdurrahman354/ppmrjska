<?php

namespace App\Filament\PPSB\Resources;

use App\Enums\JenisKelamin;
use App\Filament\PPSB\Resources\PendaftaranResource\Pages;
use App\Models\Pendaftaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->schema([
                        TextInput::make('indikator')
                            ->label('Indikator')
                            ->placeholder('Cth: Tes Bacaan, Tes Pegon')
                            ->required(),
                        TextInput::make('bobot')
                            ->label('Bobot')
                            ->minValue(0.1)
                            ->maxValue(1)
                            ->numeric()
                            ->step(0.1)
                            ->required(),
                    ])
                    ->minItems(1)
                    ->addable()
                    ->deletable()
                    ->reorderable()
                    ->columnSpanFull(),

                TagsInput::make('berkas_pendaftaran')
                    ->label('Berkas Pendaftaran')
                    ->placeholder('Cth: Foto Kartu Keluarga'),

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
                                ->reorderable(),

                    ])
                    ->columns(2)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun_pendaftaran'),
                Tables\Columns\TextColumn::make('gelombang_pendaftaran_counts')
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
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'view' => Pages\ViewPendaftaran::route('/{record}'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
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
