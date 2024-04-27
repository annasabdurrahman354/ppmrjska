<?php

namespace App\Filament\Admin\Resources\JurnalKelasResource\Pages;

use App\Enums\StatusPondok;
use App\Filament\Admin\Resources\JurnalKelasResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListJurnalKelas extends ListRecords
{
    protected static string $resource = JurnalKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('createUsingQRCode')
                ->label('Buat dengan QRCode')
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
        $semuaKelas = User::where('tanggal_lulus_pondok', null)
                            ->where('status_pondok', StatusPondok::AKTIF->value)
                            ->orderBy('kelas')
                            ->select('kelas')
                            ->distinct()
                            ->get()
                            ->pluck('kelas');

        $tabs = [
            null => Tab::make('All'),
        ];
        foreach ($semuaKelas as $kelas){
            $tabs[$kelas] = Tab::make()->query(fn ($query) => $query->whereJsonContains('kelas', (string) $kelas));
        }
        return $tabs;
    }
}
