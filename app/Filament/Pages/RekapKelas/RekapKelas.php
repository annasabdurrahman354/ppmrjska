<?php

namespace App\Filament\Pages\RekapKelas;

use App\Enums\JenisKelamin;
use App\Enums\StatusKehadiran;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class RekapKelas extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $slug = 'rekap-kelas';
    protected static ?string $navigationLabel = 'Rekap Kelas';
    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.rekap-kelas';

    #[Url]
    public ?array $tableFilters = null;

    public $activeTab = 'persen';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->select('users.*')
                    ->addSelect([
                        'total_presensi' => User::query()
                            ->selectRaw('COUNT(*)')
                            ->from('presensi_kelas')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->toBase(),
                        'hadir_count' => User::query()
                            ->selectRaw('COUNT(*)')
                            ->from('presensi_kelas')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::HADIR->value)
                            ->toBase(),
                        'telat_count' => User::query()
                            ->selectRaw('COUNT(*)')
                            ->from('presensi_kelas')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::TELAT->value)
                            ->toBase(),
                        'izin_count' => User::query()
                            ->selectRaw('COUNT(*)')
                            ->from('presensi_kelas')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::IZIN->value)
                            ->toBase(),
                        'sakit_count' => User::query()
                            ->selectRaw('COUNT(*)')
                            ->from('presensi_kelas')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::SAKIT->value)
                            ->toBase(),
                        'alpa_count' => User::query()
                            ->selectRaw('COUNT(*)')
                            ->from('presensi_kelas')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::ALPA->value)
                            ->toBase(),
                    ])
                    ->leftJoin('presensi_kelas', 'presensi_kelas.user_id', '=', 'users.id')
                    ->groupBy('users.id')
                )
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('hadir_percentage')
                    ->label('Hadir')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if ($this->activeTab === 'persen'){
                            return $query->orderByRaw('hadir_count / total_presensi ' . $direction);
                        }
                        else {
                            return $query->orderBy('hadir_count' , $direction);
                        }
                    })
                    ->getStateUsing(function ($record) {
                        if ($this->activeTab === 'persen'){
                            return $record->total_presensi ? round(($record->hadir_count / $record->total_presensi) * 100, 2) . '%' : '0%';
                        }
                        else {
                            return $record->hadir_count . 'x';
                        }
                    }),

                TextColumn::make('telat_percentage')
                    ->label('Telat')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if ($this->activeTab === 'persen'){
                            return $query->orderByRaw('telat_count / total_presensi ' . $direction);
                        }
                        else {
                            return $query->orderBy('telat_count' , $direction);
                        }
                    })
                    ->getStateUsing(function ($record) {
                        if ($this->activeTab === 'persen'){
                            return $record->total_presensi ? round(($record->telat_count / $record->total_presensi) * 100, 2) . '%' : '0%';
                        }
                        else {
                            return $record->telat_count . 'x';
                        }
                    }),

                TextColumn::make('izin_percentage')
                    ->label('Izin')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if ($this->activeTab === 'persen'){
                            return $query->orderByRaw('izin_count / total_presensi ' . $direction);
                        }
                        else {
                            return $query->orderBy('izin_count' , $direction);
                        }
                    })
                    ->getStateUsing(function ($record) {
                        if ($this->activeTab === 'persen'){
                            return $record->total_presensi ? round(($record->izin_count / $record->total_presensi) * 100, 2) . '%' : '0%';
                        }
                        else {
                            return $record->izin_count . 'x';
                        }
                    }),

                TextColumn::make('sakit_percentage')
                    ->label('Sakit')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if ($this->activeTab === 'persen'){
                            return $query->orderByRaw('sakit_count / total_presensi ' . $direction);
                        }
                        else {
                            return $query->orderBy('sakit_count' , $direction);
                        }
                    })
                    ->getStateUsing(function ($record) {
                        if ($this->activeTab === 'persen'){
                            return $record->total_presensi ? round(($record->sakit_count / $record->total_presensi) * 100, 2) . '%' : '0%';
                        }
                        else {
                            return $record->sakit_count . 'x';
                        }
                    }),

                TextColumn::make('alpa_percentage')
                    ->label('Alpa')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if ($this->activeTab === 'persen'){
                            return $query->orderByRaw('alpa_count / total_presensi ' . $direction);
                        }
                        else {
                            return $query->orderBy('alpa_count' , $direction);
                        }
                    })
                    ->getStateUsing(function ($record) {
                        if ($this->activeTab === 'persen'){
                            return $record->total_presensi ? round(($record->alpa_count / $record->total_presensi) * 100, 2) . '%' : '0%';
                        }
                        else {
                            return $record->alpa_count . 'x';
                        }
                    }),

            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->multiple()
                    ->options(User::pluck('kelas', 'kelas')->toArray()),

                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->multiple()
                    ->options(JenisKelamin::class),
                Filter::make('date_range')
                    ->columnSpan(2)
                    ->columns(2)
                    ->form([
                        DatePicker::make('mulai_tanggal')
                            ->label('Mulai Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereHas('presensiKelas.jurnalKelas', function (Builder $query) use ($data) {
                            if ($data['mulai_tanggal']) {
                                $query->where('tanggal', '>=', $data['mulai_tanggal']);
                            }
                            if ($data['sampai_tanggal']) {
                                $query->where('tanggal', '<=', $data['sampai_tanggal']);
                            }
                        });
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['mulai_tanggal'] && !$data['sampai_tanggal']) {
                            return null;
                        }
                        else if ($data['mulai_tanggal'] && !$data['sampai_tanggal']) {
                            return 'Mulai tanggal ' . Carbon::parse($data['mulai_tanggal'])->toFormattedDateString();
                        }
                        else if (!$data['mulai_tanggal'] && $data['sampai_tanggal']) {
                            return 'Sampai tanggal ' . Carbon::parse($data['sampai_tanggal'])->toFormattedDateString();
                        }
                        else if ($data['mulai_tanggal'] && $data['sampai_tanggal']) {
                            return 'Antara tanggal ' . Carbon::parse($data['mulai_tanggal'])->toFormattedDateString() . ' - ' . Carbon::parse($data['sampai_tanggal'])->toFormattedDateString();
                        }
                        return null;
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
            ])
            ->bulkActions([
            ])
            ->persistFiltersInSession();
    }
}
