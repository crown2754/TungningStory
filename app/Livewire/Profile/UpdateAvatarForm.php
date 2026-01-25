<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;

class UpdateAvatarForm extends Component
{
    public $avatars;
    public $selectedAvatarId;

    public function mount()
    {
        // 載入所有啟用的頭像
        $this->avatars = Avatar::where('is_active', true)->get();
        $this->selectedAvatarId = Auth::user()->avatar_id;
    }

    public function selectAvatar($id)
    {
        $this->selectedAvatarId = $id;
    }

    public function updateAvatar()
    {
        $user = Auth::user();
        $user->avatar_id = $this->selectedAvatarId;
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        $this->dispatch('operation-success', message: '您的容貌畫像已更新！');
    }

    public function render()
    {
        return view('livewire.profile.update-avatar-form');
    }
}
