<?php

namespace App\Livewire\Guest\DenahAreaPondok;

use App\Models\Asrama;
use App\Models\Lokasi;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public $lokasis = [];
    public $asramas = [];
    public $clickedLokasi= '';

    public function setClickedLokasi($lokasi){
        $this->clickedLokasi = $lokasi;
    }

    public function render()
    {
        $this->asramas = Asrama::query();
        if ($this->search != ''){
            $this->asramas = $this->asramas->where('nama', $this->search);
        }
        $this->lokasis = Lokasi::query();
        if ($this->search != ''){
            $this->lokasis = $this->lokasis->where('nama', $this->search);
        }

        $this->asramas = $this->asramas->get()->map(function($asrama) {
            return [
                'nama' => $asrama->nama,
                'slug' => $asrama->slug,
                'alamat' => $asrama->alamat,
                'jenis_lokasi' => 'asrama',
                'deskripsi' => $asrama->deskripsi,
                'latitude' => $asrama->latitude,
                'longitude' => $asrama->longitude,
                'thumb' => $asrama->getFirstMediaUrl('asrama_foto')
            ];
        });

        $this->lokasis = $this->lokasis->get()->map(function($lokasi) {
            return [
                'nama' => $lokasi->nama,
                'slug' => $lokasi->slug,
                'alamat' => $lokasi->alamat,
                'jenis_lokasi' => $lokasi->jenis_lokasi,
                'deskripsi' => $lokasi->deskripsi,
                'latitude' => $lokasi->latitude,
                'longitude' => $lokasi->longitude,
                'thumb' => $lokasi->getFirstMediaUrl('lokasi_foto')
            ];
        });

        $mergedData = $this->asramas->merge($this->lokasis);

        return view('livewire.guest.denah-area-pondok.index', compact('mergedData'))->layout('layouts.guest');
    }
}
