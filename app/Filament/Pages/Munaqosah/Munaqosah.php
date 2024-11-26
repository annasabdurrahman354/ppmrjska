<?php

namespace App\Filament\Pages\Munaqosah;

use App\Models\PlotJadwalMunaqosah;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Url;

class Munaqosah extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    use HasPageShield;

    protected static ?string $slug = 'munaqosah';
    protected static ?string $navigationLabel = 'Munaqosah';
    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static string $view = 'filament.pages.munaqosah';

    #[Url]
    public ?array $tableFilters = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Jadwal Munaqosah Saya')
            ->query(PlotJadwalMunaqosah::where('user_id', auth()->user()->id))
            ->columns([
                TextColumn::make('jadwalMunaqosah.materi')
                    ->label('Materi Munaqosah')
                    ->searchable(),
                TextColumn::make('jadwalMunaqosah.waktu')
                    ->label('Waktu Munaqosah')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('jadwalMunaqosah.jumlahPendaftar')
                    ->label('Pendaftar')
                    ->numeric(),
                TextColumn::make('jadwalMunaqosah.maksimal_pendaftar')
                    ->label('Maks Pendaftar')
                    ->numeric(),
                TextColumn::make('jadwalMunaqosah.batas_akhir_pendaftaran')
                    ->label('Batas Akhir Pendaftaran')
                    ->dateTime()
                    ->sortable(),
            ])

            ->actions([
                DeleteAction::make('hapus_jadwal')
                    ->label('Hapus Jadwal')
                    ->requiresConfirmation()
                    ->visible(function (PlotJadwalMunaqosah $plot) {
                        if ($plot && $plot->jadwalMunaqosah && $plot->jadwalMunaqosah->waktu >= Carbon::now()->addDay()) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->action(function (PlotJadwalMunaqosah $plot) {
                        $plot->delete();
                        redirect(Munaqosah::getUrl());
                    })
            ]);
    }
}
