<?php

namespace App\Livewire;

use App\Models\User;
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Validate;

class AuthSwitcher extends Component
{
    public $showLogin = true;
    public LoginForm $loginForm;

    // 註冊屬性
    #[Validate('required|string|max:255')]
    public $name = '';
    #[Validate('required|string|lowercase|email|max:255|unique:users')]
    public $email = '';
    #[Validate('required|string|min:8')]
    public $password = '';

    public function toggleForm()
    {
        $this->showLogin = !$this->showLogin;
        $this->resetErrorBag();
        $this->reset(['name', 'email', 'password']);
    }

    // 處理登入
    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->loginForm->email = $this->email;
        $this->loginForm->password = $this->password;
        $this->loginForm->authenticate();

        Session::regenerate();
        return $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    // 處理註冊 (加入遊戲數值)
    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'gold' => 1000,
            'stamina' => 100,
            'max_stamina' => 100,
            'job' => '平民',
            'role' => 'Player', // 預設角色為玩家
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth-switcher');
    }
}