<?php

namespace App\Filament\Resources;

use App\Enums\JenisTagihan;
use App\Enums\PeriodeTagihan;
use App\Enums\PeriodeTagihanBulanan;
use App\Enums\PeriodeTagihanSemesteran;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use App\Filament\Resources\AdministrasiResource\Pages\CreateAdministrasi;
use App\Filament\Resources\AdministrasiResource\Pages\EditAdministrasi;
use App\Filament\Resources\AdministrasiResource\Pages\ListAdministrasis;
use App\Filament\Resources\AdministrasiResource\Pages\ViewAdministrasi;
use App\Models\Administrasi;
use App\Models\PlotKamarAsrama;
use App\Models\Rekening;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Stringy\Stringy;

class AdministrasiResource extends Resource
{
    protected static ?string $model = Administrasi::class;
    protected static ?string $slug = 'administrasi';
    protected static ?string $modelLabel = 'Administrasi';
    protected static ?string $pluralModelLabel = 'Administrasi';
    protected static ?string $navigationLabel = 'Administrasi';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationGroup = 'Manajemen Administrasi';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pembayaran Administrasi')
                    ->hiddenLabel()
                    ->schema([
                        Cluster::make([
                            TextInput::make('tahun_ajaran_awal')
                                ->hiddenLabel()
                                ->required()
                                ->numeric()
                                ->default(date('Y'))
                                ->afterStateUpdated(function (Get $get, Set $set){
                                    $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                                }),
                            TextInput::make('tahun_ajaran_akhir')
                                ->hiddenLabel()
                                ->required()
                                ->numeric()
                                ->default(date('Y')+1)
                                ->gte('tahun_ajaran_awal')
                                ->afterStateUpdated(function (Get $get, Set $set){
                                    $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                                }),
                        ])
                        ->label('Tahun Ajaran'),
                        Hidden::make('tahun_ajaran'),

                        Select::make('jenis_tagihan')
                            ->label('Jenis Tagihan')
                            ->options([JenisTagihan::class])
                            ->required()
                            ->live(),

                        ToggleButtons::make('periode_pembayaran')
                            ->label('Periode Pembayaran')
                            ->options([
                                fn (Get $get) => match ($get('jenis_tagihan')) {
                                    JenisTagihan::BULANAN => PeriodeTagihanBulanan::class,
                                    JenisTagihan::SEMESTERAN => PeriodeTagihanSemesteran::class,
                                    default => [$get('tahun_ajaran') => $get('tahun_ajaran')],
                                }
                            ])
                            ->default(fn (Get $get) => $get('tahun_ajaran'))
                            ->visible(fn (Get $get) =>
                                $get('jenis_tagihan') === JenisTagihan::BULANAN->value
                                || $get('jenis_tagihan') === JenisTagihan::SEMESTERAN->value),

                        TextInput::make('biaya_administrasi')
                            ->label('Biaya Administrasi')
                            ->helperText(fn (Get $get) => 'Masukkan nominal tagihan '.JenisTagihan::class->getDeskripsi($get('jenis_tagihan')).'.')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters('.')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->suffix(',00'),

                        TextInput::make('biaya_tambahan')
                            ->label('Biaya Tambahan')
                            ->helperText(fn (Get $get) => 'Masukkan nominal tagihan '.JenisTagihan::class->getDeskripsi($get('jenis_tagihan')).'.')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters('.')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->suffix(',00')
                            ->live(true),

                        TextInput::make('deskripsi_biaya_tambahan')
                            ->label('Deskripsi Biaya Tambahan')
                            ->disabled(fn (Get $get) => !filled($get('biaya_tambahan')) || $get('biaya_tambahan') == 0)
                            ->required(fn (Get $get) => filled($get('biaya_tambahan'))),

                        DatePicker::make('batas_awal_pembayaran')
                            ->label('Batas Awal Pembayaran')
                            ->required(),

                        DatePicker::make('batas_akhir_pembayaran')
                            ->label('Batas Akhir Pembayaran')
                            ->required()
                            ->after('batas_awal_pembayaran'),

                        Select::make('rekening_id')
                            ->label('Rekening')
                            ->helperText('Nomor rekening untuk pembayaran via transfer.')
                            ->options(fn() => Rekening::all()->pluck('recordTitle', 'id'))
                            ->searchable(),
                    ]),

                Section::make('Sasaran Santri')
                    ->schema([
                        Select::make('kelas')
                            ->label('Pilih Kelas Sasaran')
                            ->multiple()
                            ->disabledOn('edit')
                            ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))
                            ->dehydrated(cant('rekap_kelas_lain_jurnal::kelas'))
                            ->maxItems(fn () => cant('rekap_kelas_lain_jurnal::kelas') ? 1 : 6)
                            ->options(
                                User::where('status_pondok', StatusPondok::AKTIF->value)
                                    ->where('tanggal_lulus_pondok', null)
                                    ->select('kelas')
                                    ->orderBy('kelas')
                                    ->distinct()
                                    ->get()
                                    ->pluck('kelas', 'kelas')
                            )
                            ->default(match (auth()->user()->kelas) {
                                config('filament-shield.super_admin.name') => ['Takmili'],
                                default => [auth()->user()->kelas]
                            })
                            ->suffixAction(
                                Action::make('loadSantriSasaran')
                                    ->label('Tampilkan Daftar Santri')
                                    ->icon('heroicon-m-user')
                                    ->requiresConfirmation()
                                    ->action(function (Get $get, Set $set, $state) {
                                        if (!PlotKamarAsrama::where('tahun_ajaran', $get('tahun_ajaran'))->exist()) {
                                            Notification::make()
                                                ->title('Ploting kamar santri untuk tahun ajaran '.$get('tahun_ajaran').' terlebih dahulu ')
                                                ->danger()
                                                ->send();
                                        }
                                        else {
                                            # TODO set tagihanAdministrasi with array consist of all user where user's kelas in kelas value
                                        }
                                    })
                            ),

                        TableRepeater::make('tagihanAdministrasi')
                            ->relationship('tagihanAdministrasi')
                            ->headers([
                                Header::make('Santri'),
                                Header::make('Jenis Kelamin'),
                                Header::make('Kelas'),
                                Header::make('Asrama'),
                                Header::make('Tagihan Asrama'),
                                Header::make('Tagihan Total'),
                            ])
                            ->schema([
                                Select::make('user_id')
                                    ->hiddenLabel()
                                    ->placeholder('Pilih santri untuk ditagih...')
                                    ->required()
                                    ->distinct()
                                    ->disabledOn('edit')
                                    ->disabled(isNotSuperAdmin())
                                    ->dehydrated(isNotSuperAdmin())
                                    ->searchable()
                                    ->preload()
                                    ->getSearchResultsUsing(fn (string $search, Get $get): array =>
                                        User::where('nama', 'like', "%{$search}%")
                                            ->whereIn('kelas', $get('../../kelas'))
                                            ->where('status_pondok',  StatusPondok::AKTIF->value)
                                            ->where('tanggal_lulus_pondok', null)
                                            ->limit(20)
                                            ->pluck('nama', 'id')
                                            ->toArray()
                                    )
                                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                                    ->columnSpan(4),

                                Placeholder::make('jenis_kelamin')
                                    ->content(fn(Get $get) => User::where('id', $get('user_id'))->first()?->jenis_kelamin),

                                Placeholder::make('kelas')
                                    ->content(fn(Get $get) => User::where('id', $get('user_id'))->first()?->kelas),

                                Placeholder::make('asrama')
                                    ->content(fn(Get $get) => User::where('id', $get('user_id'))->first()?->plotAsramaTerbaru),

                                TextInput::make('jumlah_tagihan_asrama')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                TextInput::make('jumlah_tagihan_total')
                                    ->numeric()
                                    ->default(fn (Get $get) => $get('jumlah_tagihan_administrasi') + $get('jumlah_tagihan_asrama') + $get('jumlah_tagihan_tambahan'))
                                    ->disabled()
                                    ->required()
                                    ->label('Jumlah Tagihan Total'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->visible(isSuperAdmin())
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batas_awal_pembayaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas_akhir_pembayaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_administrasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_tambahan_santri_baru')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_bank')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_rekening')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pemilik_rekening')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => ListAdministrasis::route('/'),
            'create' => CreateAdministrasi::route('/create'),
            'view' => ViewAdministrasi::route('/{record}'),
            'edit' => EditAdministrasi::route('/{record}/edit'),
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
