<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'agenda';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori_id',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
    ];

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => 'Agenda ' . $this->nama . ' (' . $this->tanggal . ')',
        );
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public static function getForm()
    {
        return [
            Section::make('Detail Agenda Pondok')
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama')
                        ->required(),
                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->required(),
                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->createOptionForm(Kategori::getForm())
                        ->createOptionUsing(function($data){
                            $kategori = new Kategori();
                            $kategori->fill($data);
                            $kategori->save();
                            return $kategori->id;
                        })
                        ->relationship('kategori', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(),
                    DatePicker::make('tanggal_awal')
                        ->label('Tanggal Awal')
                        ->required(),
                    DatePicker::make('tanggal_akhir')
                        ->label('Tanggal Akhir')
                        ->required()
                        ->after('tanggal_awal')
                ]),
        ];
    }
}
