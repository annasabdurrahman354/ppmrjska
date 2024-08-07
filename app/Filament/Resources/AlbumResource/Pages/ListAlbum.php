<?php

namespace App\Filament\Resources\AlbumResource\Pages;

use App\Filament\Resources\AgendaResource;
use App\Filament\Resources\AlbumResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAlbum extends ListRecords
{
    protected static string $resource = AlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
