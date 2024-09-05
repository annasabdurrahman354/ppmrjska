<?php

namespace App\Filament\Resources\MateriMunaqosahResource\Pages;

use App\Filament\Resources\MateriMunaqosahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMateriMunaqosah extends EditRecord
{
    protected static string $resource = MateriMunaqosahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if(isset($data['indikator_hafalan'])){
            $indikatorHafalan = $data['indikator_hafalan'];
            $indikatorHafalan = array_map(function ($string) {
                $words = explode(' ', $string);
                $words = array_map('ucfirst', $words);
                return implode(' ', $words);
            }, $indikatorHafalan);
            $data['indikator_hafalan'] = $indikatorHafalan;
        }

        if(isset($data['indikator_materi'])){
            $indikatorMateri = $data['indikator_materi'];
            $indikatorMateri = array_map(function ($string) {
                $words = explode(' ', $string);
                $words = array_map('ucfirst', $words);
                return implode(' ', $words);
            }, $indikatorMateri);
            $data['indikator_materi'] = $indikatorMateri;
        }

        return $data;
    }
}
