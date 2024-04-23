<?php

namespace App\Filament\Admin\Resources\JurnalKelasResource\Pages;

use App\Filament\Admin\Resources\JurnalKelasResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditJurnalKelas extends EditRecord
{
    protected static string $resource = JurnalKelasResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Action::make('saveTemporarily')
                ->label('Simpan Sementara')
                ->action('saveTemporarily')
                ->color('secondary'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public function saveTemporarily()
    {
        $resources = static::getResource();
        $this->save(true);
        $this->redirect($resources::getUrl('edit', ['record' => $this->record->getKey()]));
    }

    protected function getRedirectUrl(): ?string
    {
        $resources = static::getResource();
        return $resources::getUrl('index');
    }
}
