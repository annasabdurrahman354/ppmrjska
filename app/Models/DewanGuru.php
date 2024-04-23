<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DewanGuru extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'dewan_guru';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'nama_panggilan',
        'nomor_telepon',
        'email',
        'alamat',
        'status_aktif',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status_aktif' => 'boolean',
    ];
}
