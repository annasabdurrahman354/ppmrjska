<?php

namespace App\Livewire\Guest\Profil;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.guest.profil.index')->layout('layouts.guest');
    }
}
