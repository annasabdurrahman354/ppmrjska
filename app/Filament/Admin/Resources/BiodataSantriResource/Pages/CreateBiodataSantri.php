<?php

namespace App\Filament\Admin\Resources\BiodataSantriResource\Pages;

use App\Filament\Admin\Resources\BiodataSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBiodataSantri extends CreateRecord
{
    protected static string $resource = BiodataSantriResource::class;

    function get_before_dash($string) {
        $index = strpos($string, "-");
        return substr($string, 0, $index);
      }

    function get_after_dash($string): string
    {
        $index = strpos($string, "-");
        return substr($string, $index + 1);
    }

    function matchPatterProgramStudi($string): bool
    {
        $pattern = '/^[a-zA-Z]\d-\w+$/';

        if (preg_match($pattern, $string)) {
            return true;
        } else {
            return false;
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if($this->matchPatterProgramStudi($data['program_studi'])){
            $data['program_studi'] = $this->get_after_dash($data['program_studi']);
            $data['program_studi'] = $data['program_studi_jenjang'].'-'.$data['program_studi'];
        }
        else{
            $data['program_studi'] = $data['program_studi_jenjang'].'-'.$data['program_studi'];
        }
        return $data;
    }
}
