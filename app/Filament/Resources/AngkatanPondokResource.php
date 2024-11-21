<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AngkatanPondokResource\Pages;
use App\Filament\Resources\AngkatanPondokResource\RelationManagers;
use App\Models\AngkatanPondok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AngkatanPondokResource extends Resource
{
    protected static ?string $model = AngkatanPondok::class;
    protected static ?string $slug = 'angkatan-pondok';
    protected static ?string $modelLabel = 'Angkatan Pondok';
    protected static ?string $pluralModelLabel = 'Angkatan Pondok';
    protected static ?string $navigationLabel = 'Angkatan Pondok';
    protected static ?string $recordTitleAttribute = 'angkatan_pondok';

    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(AngkatanPondok::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('angkatan_pondok')
                    ->label('Angkatan Pondok')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk_takmili')
                    ->label('Tanggal Masuk Takmili')
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
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAngkatanPondoks::route('/'),
        ];
    }
}
