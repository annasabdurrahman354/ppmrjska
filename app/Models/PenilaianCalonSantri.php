<?php

namespace App\Models;

use App\Enums\StatusPenerimaan;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Mokhosh\FilamentRating\Components\Rating;

class PenilaianCalonSantri extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'penilaian_calon_santri';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'calon_santri_id',
        'penguji_id',
        'nilai_tes',
        'nilai_akhir',
        'catatan_penguji',
        'rekomendasi_penguji',
        'status_penerimaan',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $casts = [
        'nilai_tes' => 'array',
        'nilai_akhir' => 'integer',
        'catatan_penguji' => 'string',
        'rekomendasi_penguji' => 'integer',
        'status_penerimaan' => StatusPenerimaan::class,
    ];

    public function calonSantri(): BelongsTo
    {
        return $this->belongsTo(CalonSantri::class, 'calon_santri_id');
    }

    public function penguji(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }

    public static function getForm()
    {
        return [
            Select::make('calon_santri_id')
                ->label('Calon Santri')
                ->options(CalonSantri::all()->pluck('nama', 'id'))
                ->required()
                ->afterStateUpdated(
                    function (Set $set, $state){
                        if(filled($state)){
                            $calonSantri = CalonSantri::where('id', $state)->first();
                            $indikator_penilaian =  $calonSantri->gelombangPendaftaran->pendaftaran->indikator_penilaian;
                            $nilaiTesArray = array_map(function($indikator) {
                                return [
                                    'indikator' => $indikator,
                                    'nilai' => 0,
                                ];
                            }, $indikator_penilaian);
                            $set('nilai_tes', $nilaiTesArray);
                        }
                    }
                ),
            Select::make('penguji_id')
                ->label('Penguji')
                ->options(User::all()->pluck('nama', 'id'))
                ->default(auth()->id())
                ->required(),

            TableRepeater::make('nilai_tes')
                ->label('Nilai Tes')
                ->headers([
                    Header::make('Indikator Penilaian'),
                    Header::make('Nilai Tes')
                ])
                ->schema([
                    TextInput::make('indikator')
                        ->label('Indikator Penilaian'),
                    TextInput::make('nilai')
                        ->label('Nilai Tes')
                        ->numeric()
                        ->helperText('Masukkan nilai akhir untuk setiap indikator penilaian.')
                        ->required()
                ])
                ->addable(false)
                ->deletable(false)
                ->reorderable(false)
                ->cloneable(false)
                ->collapsible(false)
                ->columnSpanFull(),
            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->required(),
            Textarea::make('catatan_penguji')
                ->label('Catatan Penguji')
                ->required(),
            Rating::make('rekomendasi_penguji')
                ->label('Rekomendasi Penguji')
                ->stars(5)
                ->required(),
            ToggleButtons::make('status_penerimaan')
                ->label('Status Penerimaan')
                ->inline()
                ->grouped()
                ->options(StatusPenerimaan::class)
                ->default(StatusPenerimaan::BELUM_DITENTUKAN->value)
                ->required(),
        ];
    }
}
