<?php

namespace App\Filament\Resources\AdministrasiResource\Pages;

use App\Enums\JenisAdministrasi;
use App\Enums\KepemilikanGedung;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use App\Enums\StatusTagihan;
use App\Filament\Resources\AdministrasiResource;
use App\Filament\Resources\JurnalKelasResource;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\Rules\Unique;

class ManageTagihanAdministrasi extends ManageRelatedRecords
{
    protected static string $resource = AdministrasiResource::class;

    protected static string $relationship = 'tagihanAdministrasi';

    protected static ?string $navigationIcon = 'fluentui-people-list-24';
    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Kelola Tagihan {$recordTitle}";
    }

    public function getBreadcrumb(): string
    {
        return 'Kelola Tagihan';
    }

    public static function getNavigationLabel(): string
    {
        return 'Kelola Tagihan';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->hiddenLabel()
                    ->placeholder('Pilih santri untuk ditagih...')
                    ->required()
                    //->unique(function (Unique $rule, callable $get) {
                    //    return $rule
                    //        ->where('isprincipal', true)
                    //        ->where('user_id', $get('user_id'));
                    //}, ignoreRecord: true)
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(fn (string $search, Get $get, ManageRelatedRecords $livewire): array =>
                        User::where('nama', 'like', "%{$search}%")
                            ->whereKelasIn($livewire->getOwnerRecord()->sasaran)
                            ->whereNotIn('status_pondok', [StatusPondok::KELUAR, StatusPondok::LULUS])
                            ->whereNull('tanggal_lulus_pondok')
                            ->whereDoesntHave('tagihanAdministrasi', function ($query) use ($livewire) {
                                $query->where('administrasi_id', $livewire->getOwnerRecord()->id);
                            })
                            ->limit(20)
                            ->pluck('nama', 'id')
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                    ->columnSpan(3)
                    ->afterStateUpdated(function (Get $get, Set $set, ManageRelatedRecords $livewire, $state) {
                        $jenis_administrasi = $livewire->getOwnerRecord()->jenis_administrasi;
                        $nominal_tagihan = $livewire->getOwnerRecord()->nominal_tagihan;
                        $user = User::where('id', $state)->first();

                        $set('jenis_kelamin', $user->jenis_kelamin);
                        $set('kelas', $user->kelas);
                        $set('asrama', $user->namaAsramaTerbaru);

                        if ($jenis_administrasi === JenisAdministrasi::ASRAMA->value && $user->kepemilikanGedungPlotAsramaTerbaru === KepemilikanGedung::PPM->value) {
                            $set('jumlah_tagihan', $user->biayaAsramaTahunanTerbaru);
                        }
                        else {
                            $set('jumlah_tagihan', $nominal_tagihan);
                        }
                    }),

                TextInput::make('jenis_kelamin')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('kelas')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('asrama')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('jumlah_tagihan')
                    ->label('Tagihan Total')
                    ->default(fn (ManageRelatedRecords $livewire) => $livewire->getOwnerRecord()->nominal_tagihan)
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
            ->columns(3);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('user.nama'),
                TextEntry::make('jumlah_tagihan'),
                TextEntry::make('status_tagihan')
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

                TextColumn::make('jumlah_tagihan')
                    ->label('Jumlah Tagihan')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Tagihan')
                            ->money('IDR')
                    ]),

                TextColumn::make('status_tagihan')
                    ->label('Status Tagihan')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(isSuperAdmin()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(isSuperAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(isSuperAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(isSuperAdmin()),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(isSuperAdmin()),
            ]);
    }
}
