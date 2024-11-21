<?php

namespace App\Filament\Pages\RekapKehadiran;

use App\Enums\JenisKelamin;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use App\Filament\Exports\KehadiranExporter;
use App\Models\AngkatanPondok;
use App\Models\PresensiKelas;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class RekapKehadiran extends Page implements HasTable
{
    use InteractsWithTable;
    use HasPageShield;

    protected static ?string $slug = 'rekap-kehadiran';
    protected static ?string $navigationLabel = 'Rekap Kehadiran';
    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 54;

    protected static string $view = 'filament.pages.rekap-kehadiran';

    #[Url]
    public ?array $tableFilters = null;

    public $activeTab = 'persen';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->addSelect([
                        'total_presensi' => PresensiKelas::query()
                            ->selectRaw('COUNT(*)')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->when($this->tableFilters['date_range']['mulai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '>=', $this->tableFilters['date_range']['mulai_tanggal']);
                                });
                            })
                            ->when($this->tableFilters['date_range']['sampai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '<=', $this->tableFilters['date_range']['sampai_tanggal']);
                                });
                            })
                            ->toBase(),

                        'hadir_count' => PresensiKelas::query()
                            ->selectRaw('COUNT(*)')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::HADIR->value)
                            ->when($this->tableFilters['date_range']['mulai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '>=', $this->tableFilters['date_range']['mulai_tanggal']);
                                });
                            })
                            ->when($this->tableFilters['date_range']['sampai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '<=', $this->tableFilters['date_range']['sampai_tanggal']);
                                });
                            })
                            ->toBase(),

                        'telat_count' => PresensiKelas::query()
                            ->selectRaw('COUNT(*)')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::TELAT->value)
                            ->when($this->tableFilters['date_range']['mulai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '>=', $this->tableFilters['date_range']['mulai_tanggal']);
                                });
                            })
                            ->when($this->tableFilters['date_range']['sampai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '<=', $this->tableFilters['date_range']['sampai_tanggal']);
                                });
                            })
                            ->toBase(),

                        'izin_count' => PresensiKelas::query()
                            ->selectRaw('COUNT(*)')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::IZIN->value)
                            ->when($this->tableFilters['date_range']['mulai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '>=', $this->tableFilters['date_range']['mulai_tanggal']);
                                });
                            })
                            ->when($this->tableFilters['date_range']['sampai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '<=', $this->tableFilters['date_range']['sampai_tanggal']);
                                });
                            })
                            ->toBase(),

                        'sakit_count' => PresensiKelas::query()
                            ->selectRaw('COUNT(*)')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::SAKIT->value)
                            ->when($this->tableFilters['date_range']['mulai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '>=', $this->tableFilters['date_range']['mulai_tanggal']);
                                });
                            })
                            ->when($this->tableFilters['date_range']['sampai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '<=', $this->tableFilters['date_range']['sampai_tanggal']);
                                });
                            })
                            ->toBase(),

                        'alpa_count' => PresensiKelas::query()
                            ->selectRaw('COUNT(*)')
                            ->whereColumn('presensi_kelas.user_id', 'users.id')
                            ->where('status_kehadiran', StatusKehadiran::ALPA->value)
                            ->when($this->tableFilters['date_range']['mulai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '>=', $this->tableFilters['date_range']['mulai_tanggal']);
                                });
                            })
                            ->when($this->tableFilters['date_range']['sampai_tanggal'] ?? null, function ($query) {
                                $query->whereHas('jurnalKelas', function ($query) {
                                    $query->where('tanggal', '<=', $this->tableFilters['date_range']['sampai_tanggal']);
                                });
                            })
                            ->toBase(),

                        // Repeat for izin_count, sakit_count, and alpa_count
                    ])
                    ->groupBy('users.id')
                )
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('angkatanPondok.kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status_pondok')
                    ->label('Status Pondok')
                    ->badge()
                    ->searchable()
                    ->sortable(),

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

                TextColumn::make('total_presensi')
                    ->label('Total Presensi')
                    ->getStateUsing(function ($record) {
                        return $record->total_presensi . 'x';
                    }),

            ])
            ->filters([
                Filter::make('angkatanPondok')
                    ->form([
                        Select::make('kelas')
                            ->label('Kelas')
                            ->options(AngkatanPondok::distinct('kelas')->orderBy('kelas')->pluck('kelas', 'kelas')->toArray()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['kelas']) {
                            return $query->whereHas('angkatanPondok', function (Builder $query) use ($data) {
                                $query->where('kelas', $data['kelas']);
                            });
                        }
                        return $query;
                    }),

                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->multiple()
                    ->options(JenisKelamin::class),

                SelectFilter::make('status_pondok')
                    ->label('Status Pondok')
                    ->multiple()
                    ->default(['aktif', 'sambang', 'keperluan akademik'])
                    ->options(StatusPondok::class),

                Filter::make('date_range')
                    ->columnSpan(2)
                    ->columns(2)
                    ->form([
                        DatePicker::make('mulai_tanggal')
                            ->label('Mulai Tanggal')
                            ->live(),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal')
                            ->live(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query;
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
                ExportBulkAction::make('exportKehadiran')
                    ->exporter(KehadiranExporter::class)
            ])
            ->deferLoading()
            ->deferFilters();
    }
}
