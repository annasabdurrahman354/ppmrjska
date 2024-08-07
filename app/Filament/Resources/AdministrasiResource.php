<?php

namespace App\Filament\Resources;

use App\Enums\JenisAdministrasi;
use App\Enums\JenisTagihan;
use App\Enums\KepemilikanGedung;
use App\Enums\PeriodeTagihanBulanan;
use App\Enums\PeriodeTagihanSemesteran;
use App\Enums\StatusPondok;
use App\Enums\StatusTagihan;
use App\Filament\Resources\AdministrasiResource\Pages\CreateAdministrasi;
use App\Filament\Resources\AdministrasiResource\Pages\EditAdministrasi;
use App\Filament\Resources\AdministrasiResource\Pages\ListAdministrasis;
use App\Filament\Resources\AdministrasiResource\Pages\ManageTagihanAdministrasi;
use App\Filament\Resources\AdministrasiResource\Pages\ViewAdministrasi;
use App\Models\Administrasi;
use App\Models\Rekening;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdministrasiResource extends Resource
{
    protected static ?string $model = Administrasi::class;
    protected static ?string $slug = 'administrasi';
    protected static ?string $modelLabel = 'Administrasi';
    protected static ?string $pluralModelLabel = 'Administrasi';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Administrasi';
    protected static ?string $navigationGroup = 'Manajemen Administrasi';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 41;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Administrasi ')->columns(2)->schema([
                    Cluster::make([
                            TextInput::make('tahun_ajaran_awal')
                                ->hiddenLabel()
                                ->required()
                                ->numeric()
                                ->default(date('Y'))
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set){
                                    $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                                    if ($get('jenis_administrasi') === JenisAdministrasi::ASRAMA->value){
                                        $set('nama_administrasi', 'Tagihan Asrama TA ' . $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                                    }
                                }),
                            TextInput::make('tahun_ajaran_akhir')
                                ->hiddenLabel()
                                ->required()
                                ->numeric()
                                ->default(date('Y')+1)
                                ->gt('tahun_ajaran_awal')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set){
                                    $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                                    if ($get('jenis_administrasi') === JenisAdministrasi::ASRAMA->value){
                                        $set('nama_administrasi', 'Tagihan Asrama TA ' . $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                                    }
                                }),
                        ])
                        ->label('Tahun Ajaran')
                        ->columnSpanFull(),
                    Hidden::make('tahun_ajaran'),

                    ToggleButtons::make('jenis_administrasi')
                        ->label('Jenis Administrasi')
                        ->options(JenisAdministrasi::class)
                        ->inline()
                        ->grouped()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if ($state === JenisAdministrasi::ASRAMA->value){
                                $set('jenis_tagihan', JenisTagihan::TAHUNAN->value);
                                $set('nama_administrasi', 'Tagihan Asrama TA ' . $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                            }
                            else {
                                $set('nama_administrasi', '');
                            }
                        }),

                    ToggleButtons::make('jenis_tagihan')
                        ->label('Jenis Tagihan')
                        ->options(JenisTagihan::class)
                        ->disabled(fn (Get $get) => $get('jenis_administrasi') === JenisAdministrasi::ASRAMA->value)
                        ->dehydrated()
                        ->inline()
                        ->grouped()
                        ->required()
                        ->live(),

                    ToggleButtons::make('periode_tagihan')
                        ->label('Periode Tagihan')
                        ->options(
                            fn (Get $get) => match ($get('jenis_tagihan')) {
                                JenisTagihan::BULANAN->value => PeriodeTagihanBulanan::class,
                                JenisTagihan::SEMESTERAN->value => PeriodeTagihanSemesteran::class,
                                default => [$get('tahun_ajaran') => $get('tahun_ajaran')],
                            }
                        )
                        ->default(fn (Get $get) => $get('tahun_ajaran'))
                        ->inline()
                        ->columnSpanFull()
                        ->live()
                        ->visible(fn (Get $get) =>
                            $get('jenis_tagihan') === JenisTagihan::BULANAN->value
                            || $get('jenis_tagihan') === JenisTagihan::SEMESTERAN->value),

                    TextInput::make('nama_administrasi')
                        ->label('Nama Tagihan')
                        ->required()
                        ->readOnly(fn (Get $get, Set $set, $state) => $get('jenis_administrasi') === JenisAdministrasi::ASRAMA->value)
                        ->columnSpanFull(),
                ]),
                Section::make('Pembayaran Details')->columns(2)->schema([
                    TextInput::make('nominal_tagihan')
                        ->label(function (Get $get) {
                            if ($get('jenis_administrasi') === JenisAdministrasi::ASRAMA->value){
                                return 'Nominal Tagihan Administrasi';
                            }
                            else {
                                return 'Nominal Tagihan';
                            }
                        })
                        ->helperText(function (Get $get) {
                            if ($get('jenis_administrasi') === JenisAdministrasi::ASRAMA->value){
                                return 'Masukkan nominal tagihan administrasi non-gedung PPM' . JenisTagihan::getDeskripsi($get('jenis_tagihan')).'.';
                            }
                            else {
                                return 'Masukkan nominal tagihan' . JenisTagihan::getDeskripsi($get('jenis_tagihan')).'.';
                            }
                        })
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('Rp')
                        ->suffix(',00')
                        ->required(),

                    Select::make('rekening_id')
                        ->label('Rekening')
                        ->helperText('Nomor rekening untuk pembayaran via transfer.')
                        ->options(fn() => Rekening::all()->pluck('recordTitle', 'id'))
                        ->searchable(),

                    DatePicker::make('batas_awal_pembayaran')
                        ->label('Batas Awal Pembayaran')
                        ->required(),

                    DatePicker::make('batas_akhir_pembayaran')
                        ->label('Batas Akhir Pembayaran')
                        ->required()
                        ->after('batas_awal_pembayaran'),
                ]),
                Section::make('Sasaran')->columns(1)->schema([
                    Select::make('sasaran')
                        ->label('Pilih Kelas Sasaran')
                        ->multiple()
                        ->disabledOn('edit')
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
                        }),
                    Actions::make([
                        Action::make('tampilkanTagihanSantri')
                            ->label('Tampilkan Tagihan Santri')
                            ->disabled(fn (string $operation) => $operation != 'create')
                            ->icon('heroicon-m-user')
                            ->requiresConfirmation()
                            ->color('secondary')
                            ->action(function (Get $get, Set $set, $state) {
                                if ($get('jenis_administrasi') === JenisAdministrasi::ASRAMA->value){
                                    $userWithoutPlot = User::whereNull('tanggal_lulus_pondok')
                                        ->whereIn('kelas', $get('sasaran'))
                                        ->whereDoesntHave('plotKamarAsrama', function ($query) use ($get) {
                                            $query->where('tahun_ajaran', $get('tahun_ajaran'));
                                        })->get();

                                    if ($userWithoutPlot->isNotEmpty()) {
                                        Notification::make()
                                            ->title('Terdapat '. $userWithoutPlot->count() . ' santri yang belum diploting kamar asrama pada tahun ajaran ini!')
                                            ->danger()
                                            ->send();
                                    }

                                    $users = User::whereHas('plotKamarAsrama', function ($query) use ($state) {
                                            $query->where('tahun_ajaran', $state['tahun_ajaran_awal'].'/'.$state['tahun_ajaran_akhir'] );
                                        })
                                        ->whereNull('tanggal_lulus_pondok')
                                        ->whereIn('kelas', $state['sasaran'])
                                        ->orderBy('jenis_kelamin')
                                        ->orderBy('kelas')
                                        ->orderBy('nama')
                                        ->get();


                                    $tagihanData = $users->map(function ($user) use ($get, $state) {
                                        $tagihan = match ($user->kepemilikanGedungPlotAsramaTerbaru){
                                            KepemilikanGedung::PPM => $user->biayaAsramaTahunanTerbaru,
                                            default => $state['nominal_tagihan']
                                        };
                                        return [
                                            'user_id' => $user->id,
                                            'jenis_kelamin' => $user->jenis_kelamin,
                                            'kelas' => $user->kelas,
                                            'asrama' => $user->namaAsramaTerbaru,
                                            'jumlah_tagihan' => $tagihan,
                                            'status_tagihan' => StatusTagihan::BELUM_BAYAR,
                                        ];
                                    });


                                    $set('tagihanAdministrasi', $tagihanData->toArray());
                                }
                                else {
                                    $users = User::whereNull('tanggal_lulus_pondok')
                                        ->whereIn('kelas', $get('sasaran'))
                                        ->orderBy('jenis_kelamin')
                                        ->orderBy('kelas')
                                        ->orderBy('nama')
                                        ->get();

                                    $tagihanData = $users->map(function ($user) use ($get, $state) {
                                        return [
                                            'user_id' => $user->id,
                                            'jenis_kelamin' => $user->jenis_kelamin,
                                            'kelas' => $user->kelas,
                                            'asrama' => $user->namaAsramaTerbaru,
                                            'jumlah_tagihan' => $state['nominal_tagihan'],
                                            'status_tagihan' => StatusTagihan::BELUM_BAYAR,
                                        ];
                                    });

                                    $set('tagihanAdministrasi', $tagihanData->toArray());
                                }
                            })
                    ])->alignment(Alignment::Center),
                ]),

                Section::make('Tagihan Administrasi')->schema([
                    TableRepeater::make('tagihanAdministrasi')
                        ->relationship('tagihanAdministrasi')
                        ->default([])
                        ->headers([
                            Header::make('Santri'),
                            Header::make('Jenis Kelamin'),
                            Header::make('Kelas'),
                            Header::make('Asrama'),
                            Header::make('Jumlah Tagihan'),
                            Header::make('Status Tagihan'),
                        ])
                        ->schema([
                            Select::make('user_id')
                                ->hiddenLabel()
                                ->placeholder('Pilih santri untuk ditagih...')
                                ->required()
                                ->distinct()
                                ->searchable()
                                ->preload()
                                ->getSearchResultsUsing(fn (string $search, Get $get): array =>
                                    User::where('nama', 'like', "%{$search}%")
                                        ->whereIn('kelas', $get('../../sasaran'))
                                        ->where('tanggal_lulus_pondok', null)
                                        ->limit(20)
                                        ->pluck('nama', 'id')
                                        ->toArray()
                                    )
                                ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                                ->columnSpan(4)
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                            Placeholder::make('jenis_kelamin')
                                ->hiddenLabel()
                                ->content(fn(Get $get) => User::where('id', $get('user_id'))->first()?->jenis_kelamin->getLabel()),

                            Placeholder::make('kelas')
                                ->hiddenLabel()
                                ->content(fn(Get $get) => User::where('id', $get('user_id'))->first()?->kelas),

                            Placeholder::make('asrama')
                                ->hiddenLabel()
                                ->content(fn(Get $get) => User::where('id', $get('user_id'))->first()?->namaAsramaTerbaru),

                            TextInput::make('jumlah_tagihan')
                                ->label('Tagihan Total')
                                ->default(fn (Get $get) => $get('../../nominal_tagihan'))
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('Rp')
                                ->suffix(',00')
                                ->required(),
                            Select::make('status_tagihan')
                                ->options(StatusTagihan::class)
                                ->required(),
                        ])
                        ->addActionLabel('+ Tagihan Administrasi'),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_administrasi')
                    ->label('Nama Administrasi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_administrasi')
                    ->label('Jenis Administrasi')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_tagihan')
                    ->label('Jenis Tagihan')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('periode_tagihan')
                    ->label('Periode Tagihan')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal_tagihan')
                    ->label('Nominal Tagihan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas_awal_pembayaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas_akhir_pembayaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rekening.nomor_rekening')
                    ->label('Nomor Rekening Pembayaran')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rekening.nama_bank')
                    ->label('Nama Bank')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rekening.nama_pemilik_rekening')
                    ->label('Nama Pemilik')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rekening.kepemilikan_rekening')
                    ->sortable()
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                    //Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->selectCurrentPageOnly();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewAdministrasi::class,
            ManageTagihanAdministrasi::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdministrasis::route('/'),
            'create' => CreateAdministrasi::route('/create'),
            'view' => ViewAdministrasi::route('/{record}'),
            'edit' => EditAdministrasi::route('/{record}/edit'),
            'tagihan-administrasi' => ManageTagihanAdministrasi::route('/{record}/tagihan-administrasi'),
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
