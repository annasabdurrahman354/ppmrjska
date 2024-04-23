<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriTambahan extends Model
{
    use HasFactory;

    protected $table = 'materi_tambahan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'jumlah_halaman',
        'link_materi',
    ];

    protected $casts = [
        'nama' => 'string',
        'jumlah_halaman' => 'integer',
        'link_materi' => 'string',
    ];
}
