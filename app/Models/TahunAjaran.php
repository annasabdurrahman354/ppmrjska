<?php

namespace App\Models;

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
}
