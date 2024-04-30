<?php

namespace App\Filament\Shared\Resources\JurnalKelasResource\Pages;

use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use App\Filament\Shared\Resources\JurnalKelasResource;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageJurnalKelasPresensiKelas extends ManageRelatedRecords
{
    protected static string $resource = JurnalKelasResource::class;

    protected static string $relationship = 'presensiKelas';

    protected static ?string $navigationIcon = 'fluentui-people-list-24';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Kelola Presensi {$recordTitle}";
    }

    public function getBreadcrumb(): string
    {
        return 'Kelola Presensi';
    }

    public static function getNavigationLabel(): string
    {
        return 'Kelola Presensi';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->hiddenLabel()
                    ->placeholder('Pilih santri sesuai kelas...')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(fn (string $search, $livewire): array =>
                        User::where('nama', 'like', "%{$search}%")
                            //->whereIn('kelas', $livewire->getOwnerRecord()->kelas)
                            ->where('jenis_kelamin', $livewire->getOwnerRecord()->jenis_kelamin)
                            ->where('status_pondok', StatusPondok::AKTIF->value)
                            ->where('tanggal_lulus_pondok', null)
                            ->limit(20)
                            ->pluck('nama', 'id')
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                    ->columnSpan(4),

                ToggleButtons::make('status_kehadiran')
                    ->hiddenLabel()
                    ->inline()
                    ->grouped()
                    ->required()
                    ->options([
                        'hadir' => 'H',
                        'telat' => 'T',
                        'izin' => 'I',
                        'sakit' => 'S',
                        'alpa' => 'A',
                    ])
                    ->colors([
                        'hadir' => 'success',
                        'telat' => 'warning',
                        'izin' => 'primary',
                        'sakit' => 'secondary',
                        'alpa' => 'danger',
                    ])
                    ->default(StatusKehadiran::ALPA->value)
                    ->columnSpan(1),
            ])
            ->columns(5);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('user.nama'),
                TextEntry::make('status_kehadiran')
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

               TextColumn::make('status_kehadiran')
                    ->label('Kehadiran')
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
