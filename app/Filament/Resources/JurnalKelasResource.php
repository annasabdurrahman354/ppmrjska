<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalKelasResource\Pages\CreateJurnalKelas;
use App\Filament\Resources\JurnalKelasResource\Pages\EditJurnalKelas;
use App\Filament\Resources\JurnalKelasResource\Pages\ListJurnalKelas;
use App\Filament\Resources\JurnalKelasResource\Pages\ManagePresensiKelas;
use App\Filament\Resources\JurnalKelasResource\Pages\QRCodeCreateJurnalKelas;
use App\Filament\Resources\JurnalKelasResource\Pages\ViewJurnalKelas;
use App\Models\JurnalKelas;
use App\Models\MateriHimpunan;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JurnalKelasResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = JurnalKelas::class;
    protected static ?string $slug = 'jurnal-kelas';
    protected static ?string $modelLabel = 'Jurnal Kelas';
    protected static ?string $pluralModelLabel = 'Jurnal Kelas';
    protected static ?string $navigationLabel = 'Jurnal Kelas';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 51;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(JurnalKelas::getForm(false));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal KBM')
                    ->date()
                    ->sortable(),
                TextColumn::make('sesi')
                    ->label('Sesi KBM')
                    ->badge()
                    ->searchable(),
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Santri')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('statusKehadiranSaya')
                    ->label('Kehadiran')
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Telat' => 'primary',
                        'Izin' => 'warning',
                        'Sakit' => 'secondary',
                        'Alpa' => 'danger',
                        'Bukan Kelas' => 'gray',
                    })
                    ->badge(),
                TextColumn::make('hadirCount')
                    ->label('Hadir'),
                TextColumn::make('telatCount')
                    ->label('Telat'),
                TextColumn::make('izinCount')
                    ->label('Izin'),
                TextColumn::make('sakitCount')
                    ->label('Sakit'),
                TextColumn::make('alpaCount')
                    ->label('Alpa'),
                TextColumn::make('dewanGuru.nama')
                    ->label('Dewan Guru')
                    ->searchable(),
                TextColumn::make('materiAwal.nama')
                    ->label('Materi Awal')
                    ->sortable(),
                TextColumn::make('materiAkhir.nama')
                    ->label('Materi Akhir')
                    ->sortable(),
                TextColumn::make('halaman_awal')
                    ->label('Halaman Awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('halaman_akhir')
                    ->label('Halaman Akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ayat_awal')
                    ->label('Ayat Awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ayat_akhir')
                    ->label('Ayat Akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Detail Materi')
                    ->searchable(),
                TextColumn::make('namaPerekap')
                    ->label('Perekap'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Mulai Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Filter::make('materi')
                    ->form([
                        ToggleButtons::make('materi_type')
                                    ->label('Jenis Materi')
                                    ->inline()
                                    ->grouped()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ]),

                        Select::make('materi_id')
                            ->label('Nama Materi')
                            ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas...')
                            ->visible(fn(Get $get) => filled($get('materi_type')))
                            ->searchable()
                            ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                $get('materi_type')::where('nama', 'like', "%{$search}%")
                                    ->limit(20)->pluck('nama', 'id')
                                    ->toArray(),
                            )
                            ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                $get('materi_type')::find($value)?->nama,
                            ),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['materi_type'],
                                fn (Builder $query): Builder =>
                                    $query->when($data['materi_type'], function ($q) use ($data) {
                                        return $q->where('materi_awal_type', '=', $data['materi_type'])->orWhere('materi_akhir_type', '=', $data['materi_type']);
                                    })
                                    ->when($data['materi_id'], function ($q) use ($data) {
                                        return $q->where('materi_awal_id', '=', $data['materi_id'])->orWhere('materi_akhir_id', '=', $data['materi_id']);
                                    }),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['materi_type']) {
                            return null;
                        }

                        $namaMateri = $data['materi_type']::find($data['materi_id'])->select('nama')->first()->nama;

                        return match($data['materi_type']){
                            MateriSurat::class => 'Al Quran' . $namaMateri,
                            MateriHimpunan::class => 'Himpunan' . $namaMateri,
                            MateriTambahan::class => 'Lainnya' . $namaMateri,
                        };
                    }),

                Filter::make('halaman')
                    ->form([
                        TextInput::make('halaman')
                            ->label('Halaman')
                            ->helperText('Cari KBM yang menyampaikan halaman tertentu.')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['halaman'],
                                fn (Builder $query): Builder =>
                                    $query->where('halaman_awal', '<=', $data['halaman'])
                                        ->where('halaman_akhir', '>=', $data['halaman'])
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['halaman']) {
                            return null;
                        }

                        return 'Halaman : ' . $data['halaman'];
                    }),
            ])
            ->groups([
                Group::make('tanggal')
                    ->getTitleFromRecordUsing(fn (JurnalKelas $record): string => ucfirst($record->tanggal->format('j F, Y'))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(function (JurnalKelas $record){
                        return auth()->user()->cekPerekap($record) || isKedisiplinan() || isKeilmuan() || isAdmin();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(function (JurnalKelas $record){
                        return auth()->user()->cekPerekap($record) || isKedisiplinan() || isKeilmuan() || isAdmin();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function (JurnalKelas $record){
                        return auth()->user()->cekPerekap($record) || isKedisiplinan() || isKeilmuan() || isAdmin();
                    })
                    ->requiresConfirmation(),
                Action::make('updateMateriRekaman')
                    ->label('Ubah Materi & Rekaman')
                    ->visible(can('ubah_materi_rekaman_jurnal::kelas'))
                    ->color('secondary')
                    ->fillForm(function (JurnalKelas $record): array {
                       return [
                                'materi_awal_type' => $record->materi_awal_type,
                                'materi_akhir_type' => $record->materi_akhir_type,
                                'materi_awal_id' => $record->materi_awal_id,
                                'materi_akhir_id' => $record->materi_akhir_id,
                                'halaman_awal' => $record->halaman_awal,
                                'halaman_akhir' => $record->halaman_akhir,
                                'ayat_awal' => $record->ayat_awal,
                                'ayat_akhir' => $record->ayat_akhir,
                                'link_rekaman' => $record->link_rekaman,
                                'keterangan' =>  $record->keterangan,
                       ];})
                    ->form(JurnalKelas::getJurnalKelasMateriForm())
                    ->action(fn (array $data, JurnalKelas $record) => $record->update([
                        'materi_awal_type' => $data['materi_awal_type'],
                        'materi_akhir_type' => $data['materi_akhir_type'],
                        'materi_awal_id' => $data['materi_awal_id'],
                        'materi_akhir_id' => $data['materi_akhir_id'],
                        'halaman_awal' => $data['halaman_awal'] ?? null,
                        'halaman_akhir' => $data['halaman_akhir'] ?? null,
                        'ayat_awal' => $data['ayat_awal'] ?? null,
                        'ayat_akhir' => $data['ayat_akhir'] ?? null,
                        'link_rekaman' => $data['link_rekaman'],
                        'keterangan' => $data['keterangan'],
                    ])),

                Action::make('viewRekaman')
                    ->label('Lihat Rekaman')
                    ->modalSubmitAction(false)
                    ->visible(function (JurnalKelas $record){
                        return auth()->user()->cekKehadiran($record) || isAdmin();
                    })
                    ->color('info')
                    ->disabledForm()
                    ->fillForm(function (JurnalKelas $record): array {
                       return [
                                'materi_awal_type' => $record->materi_awal_type,
                                'materi_akhir_type' => $record->materi_akhir_type,
                                'materi_awal_id' => $record->materi_awal_id,
                                'materi_akhir_id' => $record->materi_akhir_id,
                                'halaman_awal' => $record->halaman_awal,
                                'halaman_akhir' => $record->halaman_akhir,
                                'ayat_awal' => $record->ayat_awal,
                                'ayat_akhir' => $record->ayat_akhir,
                                'link_rekaman' => $record->link_rekaman,
                                'keterangan' =>  $record->keterangan,
                       ];})
                    ->form([
                        Fieldset::make()
                            ->label('Materi Awal')
                            ->schema([
                                ToggleButtons::make('materi_awal_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->disabled()
                                    ->dehydrated(),

                                Select::make('materi_awal_id')
                                    ->hiddenLabel()
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_awal_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_awal_type')::find($value)?->nama,
                                    )
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('halaman_awal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(fn (Get $get) => ($get('materi_awal_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_awal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        Fieldset::make()
                            ->label('Materi Akhir')
                            ->schema([
                                ToggleButtons::make('materi_akhir_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->disabled()
                                    ->dehydrated(),

                                Select::make('materi_akhir_id')
                                    ->hiddenLabel()
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_akhir_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_akhir_type')::find($value)?->nama,
                                    )
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('halaman_akhir')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(fn (Get $get) => ($get('materi_akhir_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_akhir')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        TextInput::make('keterangan')
                            ->label('Detail Materi')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('link_rekaman')
                            ->label('Link Rekaman')
                            ->disabled()
                            ->dehydrated(),
                    ]),
            ])
            ->bulkActions([
                BulkAction::make('updateMateriRekaman')
                    ->visible(can('ubah_materi_rekaman_jurnal::kelas'))
                    ->label('Perbarui Materi & Rekaman')
                    ->color('secondary')
                    ->fillForm(function (Collection $records): array {
                       return [
                            'materi_awal_type' => $records->first()->materi_awal_type,
                            'materi_akhir_type' => $records->first()->materi_akhir_type,
                            'materi_awal_id' => $records->first()->materi_awal_id,
                            'materi_akhir_id' => $records->first()->materi_akhir_id,
                            'halaman_awal' => $records->first()->halaman_awal,
                            'halaman_akhir' => $records->first()->halaman_akhir,
                            'ayat_awal' => $records->first()->ayat_awal,
                            'ayat_akhir' => $records->first()->ayat_akhir,
                            'link_rekaman' => $records->first()->link_rekaman,
                            'keterangan' =>  $records->first()->keterangan,
                       ];
                    })
                    ->form(JurnalKelas::getJurnalKelasMateriForm())
                    ->action(function (array $data, Collection $records): void {
                        $records->each(
                            fn (JurnalKelas $selectedRecord) => $selectedRecord->update([
                                'materi_awal_type' => $data['materi_awal_type'],
                                'materi_akhir_type' => $data['materi_akhir_type'],
                                'materi_awal_id' => $data['materi_awal_id'],
                                'materi_akhir_id' => $data['materi_akhir_id'],
                                'halaman_awal' => $data['halaman_awal'] ?? null,
                                'halaman_akhir' => $data['halaman_akhir'] ?? null,
                                'ayat_awal' => $data['ayat_awal'] ?? null,
                                'ayat_akhir' => $data['ayat_akhir'] ?? null,
                                'link_rekaman' => $data['link_rekaman'],
                                'keterangan' => $data['keterangan'],
                            ]),
                        );
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->visible(function (){
                            return isKedisiplinan() || isKeilmuan() || isAdmin();
                        })
                ]),
            ])
            ->selectCurrentPageOnly();
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewJurnalKelas::class,
            ManagePresensiKelas::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJurnalKelas::route('/'),
            'qr-code-create' => QRCodeCreateJurnalKelas::route('/qr-code/create'),
            'create' => CreateJurnalKelas::route('/create'),
            'view' => ViewJurnalKelas::route('/{record}'),
            'edit' => EditJurnalKelas::route('/{record}/edit'),
            'presensi' => ManagePresensiKelas::route('/{record}/presensi'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'rekap_kelas_lain',
            'ubah_materi_rekaman',
        ];
    }
}
