<?php

namespace App\Filament\Resources\PlotKamarAsramaResource\Pages;


use App\Filament\Resources\PlotKamarAsramaResource;
use App\Models\Asrama;
use App\Models\KamarAsrama;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageBelumTerplotingKamarAsrama extends ManageRelatedRecords
{
    protected static string $resource = PlotKamarAsramaResource::class;

    protected static string $relationship = 'plotKamarAsrama';

    protected static ?string $navigationIcon = 'fluentui-people-list-24';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Kelola Santri Belum Terploting {$recordTitle}";
    }

    public function getBreadcrumb(): string
    {
        return 'Kelola Santri Belum Terploting';
    }

    public static function getNavigationLabel(): string
    {
        return 'Kelola Santri Belum Terploting';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Santri')
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

               Select::make('asrama_id')
                    ->label('Asrama')
                    ->required()
                    ->options(fn (Get $get): array =>
                        Asrama::where('penghuni', User::find($get('user_id'))?->jenis_kelamin)
                           ->get()
                           ->pluck('nama', 'id')
                           ->toArray()
                    )
                    ->searchable()
                    ->live(),

                Select::make('kamar_asrama_id')
                    ->label('Nomor Kamar')
                    ->required()
                    ->searchable()
                    ->options(fn (Get $get): array =>
                        KamarAsrama::where('asrama_id', $get('asrama_id'))
                            ->get()
                            ->pluck('nomor_kamar', 'id')
                            ->toArray()
                    )
                    ->afterStateHydrated(function (string $operation, Get $get, Set $set, $state) {
                        if ($operation === 'edit' && !filled($get('asrama_id'))) {
                            $set('asrama_id', KamarAsrama::find($state)?->asrama_id);
                        }
                    }),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('user.nama')
                    ->label('Santri'),
                TextEntry::make('user.jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge(),
                TextEntry::make('user.kelas')
                    ->label('Kelas'),
                TextEntry::make('kamarAsrama.asrama.nama')
                    ->label('Asrama'),
                TextEntry::make('kamarAsrama.nomor_kamar')
                    ->label('Nomor Kamar'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.nama')
            ->modifyQueryUsing()
            ->columns([
                Tables\Columns\TextColumn::make('user.nama')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(isAdmin()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(isAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(isAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(isAdmin()),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(isAdmin()),
            ]);
    }
}
