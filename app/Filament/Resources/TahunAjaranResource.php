<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAjaranResource\Pages;
use App\Models\TahunAjaran;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;
    protected static ?string $slug = 'tahun-ajaran';
    protected static ?string $modelLabel = 'Tahun Ajaran';
    protected static ?string $pluralModelLabel = 'Tahun Ajaran';
    protected static ?string $navigationLabel = 'Tahun Ajaran';
    protected static ?string $recordTitleAttribute = 'tahun_ajaran';

    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(TahunAjaran::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_awal_semester_ganjil')
                    ->label('Awal Semester Ganjil')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_akhir_semester_ganjil')
                    ->label('Akhir Semester Ganjil')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_awal_semester_genap')
                    ->label('Awal Semester Genap')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_akhir_semester_genap')
                    ->label('Akhir Semester Genap')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTahunAjarans::route('/'),
        ];
    }
}
