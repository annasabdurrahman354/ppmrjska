<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if(Auth::attempt(['email' => $this->email, 'password'=> $this->password], true)) {
            if (isAdmin()){
                return redirect()->to('/admin');
            }
            elseif (isNotAdmin()) {
                return redirect()->to('/santri');
            }
            else{
                return redirect()->route('guest.index');
            }
        } else {
            session()->flash('error', 'Alamat Email atau Password Anda salah!.');
            return redirect()->to('/login');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
