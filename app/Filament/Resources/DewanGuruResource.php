<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DewanGuruResource\Pages\CreateDewanGuru;
use App\Filament\Resources\DewanGuruResource\Pages\EditDewanGuru;
use App\Filament\Resources\DewanGuruResource\Pages\ListDewanGurus;
use App\Filament\Resources\DewanGuruResource\Pages\ViewDewanGuru;
use App\Models\DewanGuru;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DewanGuruResource extends Resource
{
    protected static ?string $model = DewanGuru::class;
    protected static ?string $slug = 'dewan-guru';
    protected static ?string $modelLabel = 'Dewan Guru';
    protected static ?string $pluralModelLabel = 'Dewan Guru';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Dewan Guru';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    //protected static ?int $navigationSort = 43;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(DewanGuru::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable(),
                TextColumn::make('nama_panggilan')
                    ->label('Nama Panggilan')
                    ->searchable(),
                TextColumn::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable(),
                IconColumn::make('status_aktif')
                    ->label('Status Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                    //Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])->selectCurrentPageOnly();
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
            'index' => ListDewanGurus::route('/'),
            'create' => CreateDewanGuru::route('/create'),
            'view' => ViewDewanGuru::route('/{record}'),
            'edit' => EditDewanGuru::route('/{record}/edit'),
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
