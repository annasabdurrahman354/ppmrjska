<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tahun_ajaran',
        'tanggal_awal_semester_ganjil',
        'tanggal_akhir_semester_ganjil',
        'tanggal_awal_semester_genap',
        'tanggal_akhir_semester_genap',
    ];

    public function plotKamarAsrama(): HasMany
    {
        return $this->hasMany(PlotKamarAsrama::class, 'tahun_ajaran', 'tahun_ajaran');
    }

    public function administrasi(): HasMany
    {
        return $this->hasMany(Administrasi::class, 'tahun_ajaran', 'tahun_ajaran');
    }

    protected function usersWithPlot(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->plotKamarAsrama()
                ->selectRaw('count(distinct user_id) as user_count')
                ->value('user_count')
        );
    }

    protected function usersWithoutPlot(): Attribute
    {
        return Attribute::make(
            get: function () {
                return DB::table('users')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('plot_kamar_asrama')
                            ->whereColumn('plot_kamar_asrama.user_id', 'users.id')
                            ->where('plot_kamar_asrama.tahun_ajaran', $this->tahun_ajaran);
                    })
                    ->count();
            }
        );
    }

    public static function getForm()
    {
        return [
            Cluster::make([
                TextInput::make('tahun_ajaran_awal')
                    ->hiddenLabel()
                    ->required()
                    ->numeric()
                    ->default(date('Y'))
                    ->live(onBlur: true)
                    ->afterStateHydrated(function (TextInput $component, Get $get, $record) {
                        if (!empty($record['tahun_ajaran'])){
                            $split_values = explode("/", $record['tahun_ajaran']);
                            $tahun_ajaran_awal = $split_values[0];
                            $component->state($tahun_ajaran_awal);
                        }
                    })
                    ->afterStateUpdated(function (Get $get, Set $set){
                        $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                    }),
                TextInput::make('tahun_ajaran_akhir')
                    ->hiddenLabel()
                    ->required()
                    ->numeric()
                    ->default(date('Y')+1)
                    ->gt('tahun_ajaran_awal')
                    ->live(onBlur: true)
                    ->afterStateHydrated(function (TextInput $component, Get $get, $record) {
                        if (!empty($record['tahun_ajaran'])){
                            $split_values = explode("/", $record['tahun_ajaran']);
                            $tahun_ajaran_akhir = $split_values[1];
                            $component->state($tahun_ajaran_akhir);
                        }
                    })
                    ->afterStateUpdated(function (Get $get, Set $set){
                        $set('tahun_ajaran', $get('tahun_ajaran_awal').'/'.$get('tahun_ajaran_akhir'));
                    }),
            ])
                ->label('Tahun Ajaran')
                ->columnSpanFull(),
            Hidden::make('tahun_ajaran')
                ->default((date('Y')).'/'.(date('Y')+1))
                ->dehydrated(),
            DatePicker::make('tanggal_awal_semester_ganjil')
                ->label('Tanggal Awal Semester Ganjil')
                ->required(),
            DatePicker::make('tanggal_akhir_semester_ganjil')
                ->label('Tanggal Akhir Semester Ganjil')
                ->after('tanggal_awal_semester_ganjil')
                ->required(),
            DatePicker::make('tanggal_awal_semester_genap')
                ->label('Tanggal Awal Semester Genap')
                ->after('tanggal_akhir_semester_ganjil')
                ->required(),
            DatePicker::make('tanggal_akhir_semester_genap')
                ->label('Tanggal Akhir Semester Genap')
                ->after('tanggal_awal_semester_genap')
                ->required(),
        ];
    }
}
