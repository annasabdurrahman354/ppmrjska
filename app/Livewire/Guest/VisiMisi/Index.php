<?php

namespace App\Livewire\Guest\VisiMisi;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.guest.visi-misi.index')->layout('layouts.guest');
    }
}
