<?php

namespace App\Livewire\Guest\PerguruanTinggiTerdekat;

use App\Models\Universitas;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public $univs = [];
    public $clickedUniv= '';

    public function setClickedUniv($univ){
        $this->clickedUniv = $univ;
    }

    public function render()
    {
        $this->univs = Universitas::query();
        if ($this->search != ''){
            $this->univs = $this->univs->where('nama', $this->search);
        }
        $this->univs = $this->univs->get();

        $this->univs = $this->univs->map(function ($item) {
            $univs = $item->toArray();
            $univs['thumb'] = $item->getFirstMediaUrl('universitas_foto');
            return $univs;
        });

        $univs = $this->univs;

        return view('livewire.guest.perguruan-tinggi-terdekat.index', compact('univs'))->layout('layouts.guest');
    }
}
