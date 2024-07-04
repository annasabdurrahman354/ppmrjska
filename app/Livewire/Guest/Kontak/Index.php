<?php

namespace App\Livewire\Guest\Kontak;

use App\Models\KotakSaran;
use Livewire\Component;

class Index extends Component
{
    public $pengirim = '';
    public $nomor_telepon = '';
    public $isi = '';

    public function render()
    {
        return view('livewire.guest.kontak.index')->layout('layouts.guest');
    }

    public function submit()
    {
        $this->validate();
        $kotakSaran = KotakSaran::create([
            'nama' => $this->pengirim,
            'nomor_telepon' => $this->nomor_telepon,
            'isi' => $this->isi,
        ]);

        return redirect()->route('guest.index');
    }

    protected function rules(): array
    {
        return [
            'pengirim' => [
                'string',
                'nullable',
            ],
            'nomor_telepon' => [
                'string',
                'nullable',
            ],
            'isi' => [
                'string',
                'required',
            ],
        ];
    }
}
