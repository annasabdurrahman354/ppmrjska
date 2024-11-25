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
    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Pendaftaran::getForm())
            ->columns(1);
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
                Tables\Columns\TextColumn::make('berkas_pendaftaran')
                    ->label('Berkas Pendaftaran')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('indikator_penilaian')
                    ->label('Indikator Penilaian')
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
                    Tables\Actions\RestoreBulkAction::make()
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
