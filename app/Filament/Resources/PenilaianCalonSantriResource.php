<?php

namespace App\Filament\Resources;

use App\Enums\StatusPenerimaan;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\CreatePenilaianCalonSantri;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\EditPenilaianCalonSantri;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\ListPenilaianCalonSantri;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\ViewPenilaianCalonSantri;
use App\Models\CalonSantri;
use App\Models\PenilaianCalonSantri;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Mokhosh\FilamentRating\Components\Rating;

class PenilaianCalonSantriResource extends Resource
{
    protected static ?string $model = PenilaianCalonSantri::class;
    protected static ?string $slug = 'penilaian-casanru';
    protected static ?string $modelLabel = 'Penilaian Casanru';
    protected static ?string $pluralModelLabel = 'Penilaian Casanru';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Penilaian Casanru';
    protected static ?string $navigationGroup = 'Manajemen Pendaftaran';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 43;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(PenilaianCalonSantri::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('calonSantri.gelombangPendaftaran.pendaftaran.tahun_pendaftaran')
                    ->label('Tahun Pendaftaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('calonSantri.gelombangPendaftaran.pendaftaran.nomor_gelombang')
                    ->label('Gelombang Pendaftaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('calonSantri.nama')
                    ->label('Nama Calon Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('penguji.nama')
                    ->label('Penguji')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai_tes')
                    ->label('Nilai Tes')
                    ->formatStateUsing(function ($state) {
                        return collect($state)->map(function ($item) {
                            return "{$item['indikator']}: {$item['nilai']}";
                        })->implode(', ');
                    })
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('catatan_penguji')
                    ->label('Catatan Penguji')
                    ->limit(50)
                    ->wrap(),
                RatingColumn::make('rekomendasi_penguji')
                    ->label('Rekomendasi Penguji'),
                Tables\Columns\TextColumn::make('status_penerimaan')
                    ->label('Status Penerimaan')
                    ->badge()
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->selectCurrentPageOnly();
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
            'index' => ListPenilaianCalonSantri::route('/'),
            'create' => CreatePenilaianCalonSantri::route('/create'),
            'view' => ViewPenilaianCalonSantri::route('/{record}'),
            'edit' => EditPenilaianCalonSantri::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
