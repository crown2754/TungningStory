<?php

namespace App\Livewire\Admin;

use App\Models\Avatar;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AvatarManagement extends Component
{
    use WithFileUploads;

    // 上傳表單欄位
    public $photo;
    public $name; // 頭像名稱

    // 篩選與列表
    public $avatars;

    public function mount()
    {
        $this->refreshAvatars();
    }

    public function refreshAvatars()
    {
        // 取得所有頭像，最新的在前面
        $this->avatars = Avatar::orderBy('created_at', 'desc')->get();
    }

    public function save()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
            'name' => 'required|string|max:20',
        ]);

        // 1. 儲存圖片
        $path = $this->photo->store('avatars', 'public');

        // 2. 寫入資料庫
        Avatar::create([
            'name' => $this->name,
            'path' => '/storage/' . $path,
            'is_active' => true,
        ]);

        // 3. 寫入操作日誌
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_AVATAR',
            'description' => "新增頭像：{$this->name}",
            'ip_address' => request()->ip(),
        ]);

        // 4. 重置表單
        $this->reset(['photo', 'name']);
        $this->refreshAvatars();
        $this->dispatch('operation-success', message: '新頭像已入庫，玩家可自由選用！');
    }

    public function delete($id)
    {
        $avatar = Avatar::find($id);

        if ($avatar) {
            // 刪除實際檔案
            $relativePath = str_replace('/storage/', '', $avatar->path);
            Storage::disk('public')->delete($relativePath);

            $avatar->delete();

            $this->refreshAvatars();
            $this->dispatch('operation-success', message: '該頭像已被銷毀！');
        }
    }

    public function render()
    {
        return view('livewire.admin.avatar-management')
            ->layout('layouts.app');
    }
}
