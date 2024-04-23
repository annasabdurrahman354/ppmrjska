<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriHafalan extends Model
{
    use HasFactory;

    protected $table = 'materi_hafalan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
    ];

    protected $casts = [
        'nama' => 'string',
    ];
}
