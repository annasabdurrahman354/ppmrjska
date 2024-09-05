<?php

namespace App\Livewire\Panel\Widgets;

use App\Enums\StatusPondok;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\PlotJadwalMunaqosah;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class JadwalMunaqosahSayaCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = JadwalMunaqosah::class;

    public function config(): array
    {
        return [
            'initialView' => 'dayGridMonth',
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'prev,next,today',
                'center' => 'title',
                'right' => 'dayGridMonth,dayGridWeek,listWeek',
            ],
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->event ?? JadwalMunaqosah::class;
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return JadwalMunaqosah::query()
            ->whereHas('materiMunaqosah', function ($query) {
                $query->where('kelas', auth()->user()->kelas);
            })
            ->where('waktu', '>=', $fetchInfo['start'])
            ->where('waktu', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (JadwalMunaqosah $jadwalMunaqosah) {
                $materiMunaqosah = $jadwalMunaqosah->materiMunaqosah;

                $isTelahMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                    ->where('waktu', '<', now())
                    ->whereHas('plotJadwalMunaqosah', fn($query) => $query->where('user_id', auth()->user()->id)->where('status_terlaksana', true))
                    ->exists();

                $isSudahAmbilBelumMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                    ->where('waktu', '>', now())
                    ->whereHas('plotJadwalMunaqosah', fn($query) => $query->where('user_id', auth()->user()->id)->where('status_terlaksana', false))
                    ->exists();

                $isLewatJadwal = $jadwalMunaqosah->waktu < now();

                $isLewatJadwalPendaftaran = $jadwalMunaqosah->batas_akhir_pendaftaran < now();

                $color = ($isTelahMelaksanakan || $isSudahAmbilBelumMelaksanakan || $isLewatJadwal || $isLewatJadwalPendaftaran) ? 'red' : 'green';

                return [
                    'id' => $jadwalMunaqosah->id,
                    'title' => $jadwalMunaqosah->recordTitleCalendar,
                    'start' => $jadwalMunaqosah->waktu,
                    //'url' => JadwalMunaqosahResource::getUrl(name: 'view', parameters: ['record' => $jadwalMunaqosah]),
                    //'shouldOpenUrlInNewTab' => true,
                    'color' => $color,
                ];
            })
            ->all();
    }

    protected function modalActions(): array
    {
        return [
            Action::make('ambilJadwal')
                ->hidden(function (JadwalMunaqosah $record) {
                    $materiMunaqosah = $record->materiMunaqosah;

                    $isTelahMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                        ->where('waktu', '<', now())
                        ->whereHas('plotJadwalMunaqosah', function ($query) {
                            $query->where('user_id', auth()->user()->id)->where('status_terlaksana', true);
                        })
                        ->exists();

                    $isSudahAmbilBelumMelaksanakan = $materiMunaqosah->jadwalmunaqosah()
                        ->where('waktu', '>', now())
                        ->whereHas('plotJadwalMunaqosah', function ($query) {
                            $query->where('user_id', auth()->user()->id)->where('status_terlaksana', false);
                        })
                        ->exists();

                    $isLewatJadwal = $record->waktu < now();
                    $isLewatJadwalPendaftaran = $record->batas_akhir_pendaftaran < now();
                    $kelasSasaran = $materiMunaqosah->kelas === auth()->user()->kelas;

                    if ($isTelahMelaksanakan || $isSudahAmbilBelumMelaksanakan || $isLewatJadwal || $isLewatJadwalPendaftaran || !$kelasSasaran) {
                        return true;
                    }
                    return false;
                })
                ->action(function (JadwalMunaqosah $record): void {
                    $plotMunaqosah = PlotJadwalMunaqosah::create([
                        'jadwal_munaqosah_id' => $record->id,
                        'user_id' => auth()->user()->id(),
                        'status_terlaksana' => false
                    ]);
                    $plotMunaqosah->save();
                })
                ->requiresConfirmation()
                ->color('warning')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalIconColor('warning')
        ];
    }

    protected function viewAction(): Action
    {
        return ViewAction::make()
            ->form(JadwalMunaqosah::getInfolist())
            ->slideOver();
    }

    protected function headerActions(): array
    {
        return [];
    }
}
