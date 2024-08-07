<?php

namespace App\Livewire\Guest\Kurikulum;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.guest.kurikulum.index')->layout('layouts.guest');
    }
}
