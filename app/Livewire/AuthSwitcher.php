<?php

namespace App\Livewire;

use Livewire\Component;

class AuthSwitcher extends Component
{
    public $showLogin = true; // 預設顯示登入表單
    public function render()
    {
        return view('livewire.auth-switcher');
    }
    public function toggleForm()
    {
        $this->showLogin = !$this->showLogin;
    }
}
