<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiKelas extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'presensi_kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jurnal_kelas_id',
        'user_id',
        'status_kehadiran',
    ];

    protected $casts = [
        'status_kehadiran' => StatusKehadiran::class,
    ];


    public function jurnalKelas(): BelongsTo
    {
        return $this->belongsTo(JurnalKelas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (PresensiKelas $record) {
            if($record->status_kehadiran == StatusKehadiran::ALPA->value){
                $record->user->notify(
                    Notification::make()
                        ->danger()
                        ->title('Tidak Menghadiri Pengajian')
                        ->body('Anda tercatat tidak hadir pada .'. $record->jurnalKelas->tanggal->format('j F, Y').' ('.$record->jurnalKelas->sesi->getLabel().')')
                        ->toDatabase(),
                );
            }
        });
    }
}
