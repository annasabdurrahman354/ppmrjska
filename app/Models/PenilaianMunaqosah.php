<?php

namespace App\Models;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                ->relationship('user', 'nama')
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

                            $indikator_materi = [];

                            foreach ($materiMunaqosah->indikator_materi as $indikator) {
                                $indikator_materi[$indikator] = 0;
                            }

                            $indikator_hafalan = [];
                            foreach ($materiMunaqosah->indikator_hafalan as $indikator) {
                                $indikator_hafalan[$indikator] = 0;
                            }

                            $set('nilai_materi', $indikator_materi);
                            $set('nilai_hafalan', $indikator_hafalan);

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

            KeyValue::make('nilai_materi')
                ->label('Nilai Munaqosah Materi')
                ->columnSpanFull()
                ->keyLabel('Indikator Penilaian')
                ->keyPlaceholder('Indikator Penilaian')
                ->editableKeys(false)
                ->addable(false)
                ->deletable(false)
                ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),

            KeyValue::make('nilai_hafalan')
                ->label('Nilai Munaqosah Hafalan')
                ->columnSpanFull()
                ->keyLabel('Indikator Penilaian')
                ->keyPlaceholder('Indikator Penilaian')
                ->editableKeys(false)
                ->addable(false)
                ->deletable(false)
                ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),
        ];
    }
}
