<?php

namespace App\Filament\Santri\Resources\JurnalKelasResource\Pages;

use App\Filament\Santri\Resources\JurnalKelasResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateJurnalKelas extends CreateRecord
{
    protected static string $resource = JurnalKelasResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);
    
        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            Action::make('saveTemporarily')
                ->label('Simpan Sementara')
                ->action('saveTemporarily')
                ->color('secondary'),
            //$this->getCreateAnotherFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCancelFormAction(): Action
    {
        $resources = static::getResource();
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.cancel.label'))
            ->url($resources::getUrl('index'))
            ->color('gray');
    }

    public function saveTemporarily()
    {
        $resources = static::getResource();
        //dd($this->data);
        $this->create();
        $this->redirect($resources::getUrl('edit', ['record' => $this->record->getKey()]));
    }
}
