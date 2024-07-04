<?php

namespace App\Filament\Resources\PlotKamarAsramaResource\RelationManagers;

use App\Models\Asrama;
use App\Models\KamarAsrama;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PlotKamarAsramaRelationManager extends RelationManager
{
    protected static string $relationship = 'plotKamarAsrama';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                        User::where('nama', 'like', "%{$search}%")
                            ->limit(10)->pluck('nama', 'id')
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                        User::find($value)?->nama
                    )
                    ->live(),

                Forms\Components\Select::make('asrama_id')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                        Asrama::where('penghuni', User::find($get('user_id'))?->jenis_kelamin)
                            ->limit(10)->pluck('nama', 'id')
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                        Asrama::find($value)?->nama
                    )
                    ->live(),

                Forms\Components\Select::make('kamar_asrama_id')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                        KamarAsrama::where('asrama_id', $get('asrama_id'))
                            ->limit(10)->pluck('nomor_kamar', 'id')
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                        KamarAsrama::find($value)?->nomor_kamar
                    )
                    ->live(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.nama')
            ->columns([
                Tables\Columns\TextColumn::make('user.nama')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kamarAsrama.asrama.nama')
                    ->label('Asrama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kamarAsrama.nomor_kamar')
                    ->label('Nomor Kamar')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
