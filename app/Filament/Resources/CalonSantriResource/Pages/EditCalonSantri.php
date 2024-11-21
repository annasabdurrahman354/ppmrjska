<?php

namespace App\Filament\Resources\CalonSantriResource\Pages;

use App\Filament\Resources\CalonSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalonSantri extends EditRecord
{
    protected static string $resource = CalonSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(isAdmin()),
        ];
    }

    function get_before_dash($string) {
        $index = strpos($string, "-");
        return substr($string, 0, $index);
      }

    function get_after_dash($string) {
        $index = strpos($string, "-");
        return substr($string, $index + 1);
    }

    function matchPatternProgramStudi($string) {
        $pattern = '/^[a-zA-Z]\d-\w+$/';

        if (preg_match($pattern, $string)) {
            return true;
        } else {
            return false;
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if($this->matchPatternProgramStudi($data['program_studi'])){
            $jenjang = $this->get_before_dash($data['program_studi']);
            $prodi = $this->get_after_dash($data['program_studi']);
            $data['program_studi_jenjang'] = $jenjang;
            $data['program_studi'] = $prodi;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($this->matchPatternProgramStudi($data['program_studi'])){
            $data['program_studi'] = $this->get_after_dash($data['program_studi']);
            $data['program_studi'] = $data['program_studi_jenjang'].'-'.$data['program_studi'];
        }
        else{
            $data['program_studi'] = $data['program_studi_jenjang'].'-'.$data['program_studi'];
        }

        return $data;
    }
}
