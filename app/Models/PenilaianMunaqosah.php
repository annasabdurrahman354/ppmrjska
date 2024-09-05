<?php

namespace App\Models;

use App\Enums\StatusPondok;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenilaianMunaqosah extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'penilaian_munaqosah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'materi_munaqosah_id',
        'nilai_materi',
        'nilai_hafalan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nilai_materi' => 'array',
        'nilai_hafalan' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materiMunaqosah(): BelongsTo
    {
        return $this->belongsTo(MateriMunaqosah::class);
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Penilaian Munaqosah '.$this->user->nama.': '. $this->materiMunaqosah->jenis_materi->getLabel(). ' ('.$this->materiMunaqosah->tahun_ajaran.')',
        );
    }

    public static function getForm()
    {
        return [
            Select::make('user_id')
                ->label('Santri')
                ->relationship(
                    'user',
                    'nama',
                    modifyQueryUsing: fn (Builder $query, Get $get) =>
                        $query->whereNotIn('status_pondok', [StatusPondok::KELUAR, StatusPondok::LULUS])
                            ->whereNull('tanggal_lulus_pondok')
                )
                ->searchable()
                ->required()
                ->live(),

            Select::make('materi_munaqosah_id')
                ->label('Materi Munaqosah')
                ->options(MateriMunaqosah::all()->sortBy('created_at')->pluck('recordTitle', 'id'))
                ->searchable()
                ->required()
                ->live()
                ->afterStateUpdated(
                    function (Set $set, $state){
                        if(filled($state)){
                            $materiMunaqosah = MateriMunaqosah::where('id', $state)->first();

                            $nilai_materi = [];
                            $nilai_hafalan = [];

                            foreach ($materiMunaqosah->indikator_materi as $indikator) {
                                $nilai_materi[] = [
                                    'indikator' => $indikator,
                                    'nilai' => 0
                                ];
                            }


                            foreach ($materiMunaqosah->indikator_hafalan as $indikator) {
                                $nilai_hafalan[] = [
                                    'indikator' => $indikator,
                                    'nilai' => 0
                                ];
                            }

                            $set('nilai_materi', $nilai_materi);
                            $set('nilai_hafalan', $nilai_hafalan);

                            $set('materi', $materiMunaqosah->materi);
                            $set('hafalan', $materiMunaqosah->hafalan);
                        }
                    }
                ),

            TagsInput::make('materi')
                ->label('Materi')
                ->placeholder('Materi yang diujikan')
                ->disabled()
                ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),

            TagsInput::make('hafalan')
                ->label('Hafalan')
                ->placeholder('Hafalan yang diujikan')
                ->disabled()
                ->dehydrated()
                ->default(function (Get $get){
                    return filled($get('materi_munaqosah_id')) ?
                        MateriMunaqosah::where('id', $get('materi_munaqosah_id'))->first()->hafalan
                        : [];
                })
                ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),

            TableRepeater::make('nilai_materi')
                ->label('Nilai Munaqosah Materi')
                ->columnSpanFull()
                ->addable(false)
                ->deletable(false)
                ->visible(fn(Get $get) => filled($get('materi_munaqosah_id')))
                ->headers([
                    Header::make('Indikator'),
                    Header::make('Nilai')
                ])
                ->schema([
                    TextInput::make('indikator')
                        ->disabled()->dehydrated(),
                    TextInput::make('nilai')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required()
                ]),

            TableRepeater::make('nilai_hafalan')
                ->label('Nilai Munaqosah Hafalan')
                ->columnSpanFull()
                ->addable(false)
                ->deletable(false)
                ->visible(fn(Get $get) => filled($get('materi_munaqosah_id')))
                ->headers([
                    Header::make('Indikator'),
                    Header::make('Nilai')
                ])
                ->schema([
                    TextInput::make('indikator')
                        ->disabled()->dehydrated(),
                    TextInput::make('nilai')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required()
                ]),
        ];
    }
}
