<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdministrasiResource\Pages;
use App\Models\Administrasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdministrasiResource extends Resource
{
    protected static ?string $model = Administrasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->hiddenLabel()
                    ->schema([
                        Cluster::make([
                            TextInput::make('tahun_ajaran_awal')
                                ->hiddenLabel()
                                ->required()
                                ->numeric()
                                ->default(date('Y')),
                            TextInput::make('tahun_ajaran_akhir')
                                ->hiddenLabel()
                                ->required()
                                ->numeric()
                                ->default(date('Y')+1)
                                ->gte('tahun_ajaran_awal'),
                        ])
                        ->label('Tahun Ajaran'),

                        DatePicker::make('batas_awal_pembayaran')
                            ->label('Batas Awal Pembayaran')
                            ->required(),

                        DatePicker::make('batas_akhir_pembayaran')
                            ->label('Batas Akhir Pembayaran')
                            ->required(),

                        TextInput::make('biaya_administrasi')
                            ->label('Biaya Administrasi')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters('.')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->suffix(',00'),

                        TextInput::make('biaya_tambahan_santri_baru')
                            ->label('Biaya Tambahan Santri Baru')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters('.')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->suffix(',00'),
                        ]),

                Section::make('Rekening Pembayaran')
                    ->schema([
                        TextInput::make('nama_bank')
                            ->label('Nama Bank')
                            ->required(),

                        TextInput::make('nomor_rekening')
                            ->label('Nomor Rekening')
                            ->type('number')
                            ->required(),

                        TextInput::make('nama_pemilik_rekening')
                            ->label('Nama Pemilik Rekening')
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->visible(isSuperAdmin())
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batas_awal_pembayaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas_akhir_pembayaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_administrasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_tambahan_santri_baru')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_bank')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_rekening')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pemilik_rekening')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAdministrasis::route('/'),
            'create' => Pages\CreateAdministrasi::route('/create'),
            'view' => Pages\ViewAdministrasi::route('/{record}'),
            'edit' => Pages\EditAdministrasi::route('/{record}/edit'),
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
