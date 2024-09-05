<?php

namespace App\Filament\Resources\JadwalMunaqosahResource\Pages;

use App\Enums\StatusPondok;
use App\Filament\Resources\JadwalMunaqosahResource;
use App\Models\JadwalMunaqosah;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageJadwalMunaqosahPlotJadwalMunaqosah extends ManageRelatedRecords
{
    protected static string $resource = JadwalMunaqosahResource::class;

    protected static string $relationship = 'plotJadwalMunaqosah';

    protected static ?string $navigationIcon = 'fluentui-people-list-24';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Kelola Plot Jadwal Munaqosah {$recordTitle}";
    }

    public function getBreadcrumb(): string
    {
        return 'Kelola Plot Jadwal Munaqosah';
    }

    public static function getNavigationLabel(): string
    {
        return 'Kelola Plot Jadwal Munaqosah';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->hiddenLabel()
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih santri sesuai kelas munaqosah...')
                    ->getSearchResultsUsing(function (string $search, JadwalMunaqosah $record): array{
                        $materiMunaqosah = $record;
                        $kelas = $materiMunaqosah->kelas;
                        return User::where('nama', 'like', "%{$search}%")
                            ->whereKelas($kelas)
                            ->whereNotIn('status_pondok', [StatusPondok::KELUAR, StatusPondok::LULUS])
                            ->whereNull('tanggal_lulus_pondok')
                            ->limit(20)
                            ->pluck('nama', 'id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                    ->columnSpan(4),
                Toggle::make('status_terlaksana')
                    ->label('Terlaksana?')
                    ->default(false)
                    ->required()
                    ->columnSpan(1),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('user.nama'),
                TextEntry::make('status_terlaksana')
                    ->badge(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('user.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

               TextColumn::make('status_terlaksana')
                    ->label('Terlaksana')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ]);
    }
}
