<?php

namespace App\Models;

use App\Enums\JenjangKelas;
use Awcodes\Shout\Components\Shout;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kurikulum extends Model
{
    use HasFactory;

    protected $table = 'kurikulum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'angkatan_pondok',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'angkatan_pondok' => 'integer',
    ];

    public function plotKurikulum(): HasMany
    {
        return $this->hasMany(PlotKurikulum::class);
    }

    public static function getForm()
    {
       return [
            TextInput::make('angkatan_pondok')
                ->label('Angkatan Pondok')
                ->placeholder('Isi angkatan pondok...')
                ->required()
                ->numeric()
                ->minValue(2015)
                ->unique(ignoreRecord: true)
                ->columnSpanFull(),

            Repeater::make('plotKurikulum')
                ->hiddenLabel()
                ->relationship('plotKurikulum')
                ->itemLabel(function ($uuid, $component) {
                    $keys = array_keys($component->getState());
                    $index = array_search($uuid, $keys);
                    return "Semester ".$index + 1;
                })
                ->schema([
                    Select::make('jenjang_kelas')
                        ->required()
                        ->distinct()
                        ->options(JenjangKelas::class),
                    Shout::make('st-empty')
                        ->content('Belum ada plotingan materi kurikulum!')
                        ->type('info')
                        ->color(Color::Yellow)
                        ->visible(fn(Get $get) => !filled($get('plotKurikulumMateri'))),
                    TableRepeater::make('plotKurikulumMateri')
                        ->hiddenLabel()
                        ->relationship('plotKurikulumMateri')
                        ->headers([
                            Header::make('Jenis Materi'),
                            Header::make('Nama Materi'),
                            Header::make('Status Ketercapaian')
                        ])
                        ->schema([
                            ToggleButtons::make('materi_type')
                                ->hiddenLabel()
                                ->required()
                                ->inline()
                                ->options([
                                    MateriJuz::class => 'Al-Quran',
                                    MateriHimpunan::class => 'Himpunan',
                                    MateriHafalan::class => 'Hafalan',
                                    MateriTambahan::class => 'Lainnya',
                                ])
                                ->default(MateriJuz::class)
                                ->live()
                                ->afterStateUpdated(function(Set $set) {
                                    $set('materi_id', null);
                                }),

                            Select::make('materi_id')
                                ->hiddenLabel()
                                ->placeholder('Pilih nomor juz Al-Quran...')
                                ->required()
                                ->hidden(fn (Get $get) => $get('materi_type') == null || $get('materi_type') != MateriJuz::class)
                                ->options(
                                    MateriJuz::all('nama', 'id')
                                        ->pluck('nama', 'id')
                                        ->sortBy('nama')
                                        ->toArray(),
                                )
                                ->preload(),

                            Select::make('materi_id')
                                ->hiddenLabel()
                                ->required()
                                ->placeholder('Pilih materi himpunan/materi kelas/hafalan...')
                                ->hidden(fn (Get $get) => $get('materi_type') == null || $get('materi_type') == MateriJuz::class)
                                ->searchable()
                                ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                $get('materi_type')::where('nama', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->pluck('nama', 'id')
                                    ->sortBy('nama')
                                    ->toArray(),
                                )
                                ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                $get('materi_type')::find($value)?->nama,
                                ),

                            Toggle::make('status_tercapai')
                                ->onColor('success')
                                ->offColor('danger')
                        ])
                        ->live()
                        ->addable(true)
                        ->addActionLabel('Tambah Materi +')
                        ->reorderableWithButtons()
                        ->deletable()
                        ->deleteAction(
                            fn (Action $action) => $action->requiresConfirmation(),
                        )
                ])
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $state): array {
                    $targetIndex = null;
                    $values = array_values($state);
                    foreach ($values as $index => $record) {
                        if ($record['jenjang_kelas'] === $data['jenjang_kelas']) {
                            $targetIndex = $index + 1;
                            break;
                        }
                    }
                    $data['semester'] = $targetIndex;
                    return $data;
                })
                ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $state): array {
                    $targetIndex = null;
                    $values = array_values($state);
                    foreach ($values as $index => $record) {
                        if ($record['jenjang_kelas'] === $data['jenjang_kelas']) {
                            $targetIndex = $index + 1;
                            break;
                        }
                    }
                    $data['semester'] = $targetIndex;
                    return $data;
                })
                ->columnSpanFull()
                ->addable(true)
                ->addActionLabel('Tambah Semester +')
                ->reorderableWithButtons()
                ->deletable()
                ->deleteAction(
                    fn (Action $action) => $action->requiresConfirmation(),
                )
                ->dehydrated()
        ];
    }
}
