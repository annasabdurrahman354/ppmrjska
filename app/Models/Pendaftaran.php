<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    use SoftCascadeTrait;

    protected $softCascade = ['gelombangPendaftaran'];

    protected $table = 'pendaftaran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'tahun_pendaftaran',
        'kontak_panitia',
        'kontak_pengurus',
        'berkas_pendaftaran',
        'indikator_penilaian',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tahun_pendaftaran' => 'integer',
        'kontak_panitia' => 'array',
        'kontak_pengurus' => 'array',
        'berkas_pendaftaran' => 'array',
        'indikator_penilaian' => 'array',
    ];

    public function gelombangPendaftaran(): HasMany
    {
        return $this->hasMany(GelombangPendaftaran::class);
    }

    function calonSantri(): HasManyThrough
    {
        return $this->hasManyThrough(CalonSantri::class, GelombangPendaftaran::class);
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Pendaftaran '.(string) $this->tahun_pendaftaran,
        );
    }

    public static function getForm()
    {
        return [
            TextInput::make('tahun_pendaftaran')
                ->label('Tahun Pendaftaran')
                ->integer()
                ->minValue(2011),
            TableRepeater::make('kontak_panitia')
                ->label('Kontak Panitia')
                ->addActionLabel('+ Tambah Kontak Panitia')
                ->headers([
                    Header::make('Nama Panitia'),
                    Header::make('Nomor Telepon'),
                    Header::make('Jenis Kelamin'),
                ])
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Panitia')
                        ->required(),
                    TextInput::make('nomor_telepon')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->required(),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(JenisKelamin::class)
                        ->required(),
                ])
                ->minItems(1)
                ->addable()
                ->deletable()
                ->reorderable()
                ->columnSpanFull(),

            TableRepeater::make('kontak_pengurus')
                ->label('Kontak Pengurus')
                ->addActionLabel('+ Tambah Kontak Pengurus')
                ->headers([
                    Header::make('Nama Panitia'),
                    Header::make('Nomor Telepon'),
                    Header::make('Jenis Kelamin'),
                ])
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Panitia')
                        ->required(),
                    TextInput::make('nomor_telepon')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->required(),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(JenisKelamin::class)
                        ->required(),
                ])
                ->addable()
                ->deletable()
                ->reorderable()
                ->columnSpanFull(),

            TagsInput::make('indikator_penilaian')
                ->label('Indikator Penilaian')
                ->helperText('Contoh: Tes Bacaan, Tes Pegon'),

            TagsInput::make('berkas_pendaftaran')
                ->label('Berkas Pendaftaran')
                ->helperText('Contoh: Foto Kartu Keluarga, Foto KTP'),

            Repeater::make('gelombangPendaftaran')
                ->relationship('gelombangPendaftaran')
                ->label('Gelombang Pendaftaran')
                ->addActionLabel('+ Tambah Gelombang Pendaftaran')
                ->schema([
                    TextInput::make('nomor_gelombang')
                        ->integer()
                        ->required(),
                    TextInput::make('link_grup')
                        ->label('Link Grup')
                        ->url()
                        ->required(),
                    DateTimePicker::make('batas_awal_pendaftaran')
                        ->label('Batas Mulai Pendaftaran')
                        ->beforeOrEqual('batas_akhir_pendaftaran')
                        ->required(),
                    DateTimePicker::make('batas_akhir_pendaftaran')
                        ->label('Batas Akhir Pendaftaran')
                        ->afterOrEqual('batas_awal_pendaftaran')
                        ->required(),
                    TableRepeater::make('timeline')
                        ->label('Timeline')
                        ->addActionLabel('+ Tambah Timeline')
                        ->headers([
                            Header::make('Rundown'),
                            Header::make('Tanggal'),
                        ])
                        ->schema([
                            Select::make('rundown')
                                ->label('Rundown')
                                ->placeholder('Cth: Mulai Pendaftaran, Daftar Ulang, Osanru')
                                ->required(),
                            DatePicker::make('tanggal')
                                ->label('Tanggal')
                                ->required()
                        ])
                        ->minItems(1)
                        ->addable()
                        ->deletable()
                        ->reorderable()
                        ->columnSpanFull(),

                ])
                ->columns(2)
        ];
    }
}
