<?php

namespace App\Livewire\Admin;

use App\Models\Npc;
use App\Models\Avatar;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth; // 引入 Auth 以取得操作者資訊

class NpcManagement extends Component
{
    use WithPagination;

    // 表單欄位
    public $name, $title, $description, $greeting, $avatar_id, $location;
    public $is_active = true;
    public $editingNpcId = null; // 編輯模式用

    public $showModal = false; // 控制彈出視窗

    protected $rules = [
        'name' => 'required|string|max:20',
        'title' => 'required|string|max:20',
        'description' => 'nullable|string|max:500',
        'greeting' => 'nullable|string|max:255',
        'avatar_id' => 'nullable|exists:avatars,id',
        'location' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.admin.npc-management', [
            'npcs' => Npc::with('avatar')->orderBy('created_at', 'desc')->paginate(10),
            'avatars' => Avatar::where('is_active', true)->get(), // 供下拉選單用
        ])->layout('layouts.app');
    }

    // 開啟新增視窗
    public function create()
    {
        $this->resetInput();
        $this->editingNpcId = null;
        $this->showModal = true;
    }

    // 開啟編輯視窗
    public function edit($id)
    {
        $npc = Npc::find($id);
        if ($npc) {
            $this->editingNpcId = $npc->id;
            $this->name = $npc->name;
            $this->title = $npc->title;
            $this->description = $npc->description;
            $this->greeting = $npc->greeting;
            $this->avatar_id = $npc->avatar_id;
            $this->location = $npc->location;

            // [修正] 強制轉型為布林值，解決 Checkbox 狀態顯示問題
            $this->is_active = (bool) $npc->is_active;

            $this->showModal = true;
        }
    }

    // 儲存 (新增或更新)
    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'greeting' => $this->greeting,
            'avatar_id' => $this->avatar_id ?: null, // 空字串轉 null
            'location' => $this->location,
            'is_active' => $this->is_active,
        ];

        $user = Auth::user();

        if ($this->editingNpcId) {
            // === 更新模式 ===
            $npc = Npc::find($this->editingNpcId);

            // 1. 取得修改前的舊資料
            $oldData = $npc->only(array_keys($data));

            // 2. 執行更新
            $npc->update($data);

            // 3. 取得有變動的欄位
            $changes = $npc->getChanges();

            // 4. 過濾出真正被我們修改的欄位 (排除 updated_at 等系統欄位)
            $realChanges = array_intersect_key($changes, $data);

            // 5. 若有實質變動，寫入 AuditLog
            if (!empty($realChanges)) {
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'NPC_UPDATE',
                    'description' => "修改了 NPC：{$npc->name} ({$npc->title})",
                    'changes' => [
                        'before' => array_intersect_key($oldData, $realChanges), // 舊值
                        'after' => $realChanges, // 新值
                    ],
                ]);
            }

            $this->dispatch('operation-success', message: 'NPC 資料已更新！');
        } else {
            // === 新增模式 ===
            $npc = Npc::create($data);

            // 寫入新增紀錄
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'NPC_CREATE',
                'description' => "新增了 NPC：{$npc->name} ({$npc->title})",
                'changes' => $data,
            ]);

            $this->dispatch('operation-success', message: '新 NPC 已報到！');
        }

        $this->showModal = false;
        $this->resetInput();
    }

    // 刪除
    public function delete($id)
    {
        $npc = Npc::find($id);

        if ($npc) {
            // 寫入刪除紀錄 (含備份資料)
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'NPC_DELETE',
                'description' => "刪除了 NPC：{$npc->name}",
                'changes' => $npc->toArray(), // 備份整筆資料
            ]);

            $npc->delete();
            $this->dispatch('operation-success', message: '該 NPC 已被移除。');
        }
    }

    public function resetInput()
    {
        $this->reset(['name', 'title', 'description', 'greeting', 'avatar_id', 'location', 'is_active', 'editingNpcId']);
    }
}
