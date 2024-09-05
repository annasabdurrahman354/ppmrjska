<?php

namespace App\Filament\Resources\JurnalKelasResource\Pages;

use App\Enums\StatusPondok;
use App\Filament\Resources\JurnalKelasResource;
use App\Models\AngkatanPondok;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListJurnalKelas extends ListRecords
{
    protected static string $resource = JurnalKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('createUsingQRCode')
                ->label('Buat dengan QRCode')
                ->visible(can('create_jurnal::kelas'))
                ->action('createUsingQRCode')
                ->color('secondary'),
        ];
    }


    public function createUsingQRCode()
    {
        $resources = static::getResource();
        $this->redirect($resources::getUrl('qr-code-create'));
    }

    public function getTabs(): array
    {
        $semuaKelas = AngkatanPondok::whereHas('users', function ($query) {
                $query->whereIn('status_pondok', [StatusPondok::AKTIF, StatusPondok::KEPERLUAN_AKADEMIK, StatusPondok::SAMBANG, StatusPondok::NONAKTIF]);
            })
            ->distinct()
            ->pluck('kelas');

        $alumni = AngkatanPondok::whereDoesntHave('users', function ($query) {
            $query->whereIn('status_pondok', [StatusPondok::AKTIF, StatusPondok::KEPERLUAN_AKADEMIK, StatusPondok::SAMBANG, StatusPondok::NONAKTIF]);
            })
            ->distinct()
            ->pluck('kelas');

        $tabs = [
            null => Tab::make('All'),
            'Kelas Saya' => Tab::make()->query(fn ($query) => $query->whereHas('presensiKelas', function($q){
                $q->where('user_id', auth()->id());
            })),
        ];
        foreach ($semuaKelas as $kelas){
            $tabs[$kelas] = Tab::make()->query(fn ($query) => $query->whereJsonContains('kelas', (string) $kelas));
        }
        $tabs['Alumni'] = Tab::make()->query(fn ($query) => $query->whereJsonContains('kelas', $alumni));
        return $tabs;
    }

    protected function getTableQuery() : Builder
    {
        $user =  auth()->user();
        $model = (new (static::$resource::getModel()))->query();

        if (isNotSuperAdmin() && !isKeilmuan()){
            $model = $model->where('jenis_kelamin', $user->jenis_kelamin);
        }

        return $model;
    }
}
