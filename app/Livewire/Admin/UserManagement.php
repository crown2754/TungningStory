<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $editingUser = null; // 當前正在編輯的 User Model

    // 編輯表單欄位
    public $form = [
        'name' => '',
        'email' => '',
        'role' => '',
        'job' => '',
        'gold' => 0,
        'stamina' => 0,
        'max_stamina' => 0,
    ];

    public $isModalOpen = false;

    // 當搜尋字串改變時，重置分頁到第一頁
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // 開啟編輯視窗
    public function edit($userId)
    {
        $this->editingUser = User::findOrFail($userId);

        // 將資料填入表單
        $this->form = [
            'name' => $this->editingUser->name,
            'email' => $this->editingUser->email,
            'role' => $this->editingUser->role,
            'job' => $this->editingUser->job,
            'gold' => $this->editingUser->gold,
            'stamina' => $this->editingUser->stamina,
            'max_stamina' => $this->editingUser->max_stamina,
        ];

        $this->isModalOpen = true;
    }

    // 儲存修改
    public function update()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();
        $targetUser = $this->editingUser;

        // --- [新增] 準備紀錄變更 ---
        $changes = [];

        // 取得原本的資料 (只取我們表單有在用的欄位)
        $originalData = $targetUser->only(array_keys($this->form));

        // 比對差異
        foreach ($this->form as $key => $newValue) {
            // 如果新值與舊值不同 (注意弱型別比較，避免 '100' != 100 的問題)
            if ($originalData[$key] != $newValue) {
                $changes[$key] = [
                    'from' => $originalData[$key],
                    'to' => $newValue,
                ];
            }
        }

        // 1. 只有 OM 可以修改權限 (GM 只能改數值)
        // 如果當前操作者不是 OM，強制將表單中的 role 改回目標原本的 role
        if (!$currentUser->isOM()) {
            $this->form['role'] = $targetUser->role;
        }

        // 驗證輸入
        $this->validate([
            'form.role' => 'required|in:Player,GM,OM',
            'form.gold' => 'required|integer|min:0',
            'form.stamina' => 'required|integer|min:0',
            'form.max_stamina' => 'required|integer|min:1',
            'form.job' => 'required|string',
        ]);

        // --- [核心權限保護邏輯] ---

        // 2. 避免運營總督 (OM) 被拔權
        // 如果目標原本是 OM，且新設定的權限不是 OM，則禁止修改 (無論是誰操作)
        if ($targetUser->isOM() && $this->form['role'] !== User::ROLE_OM) {
            // 這裡可以選擇報錯，或者默默忽略
            // [新增] A. 寫入違規操作紀錄 (留存證據)
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'DENIED_OM_DOWNGRADE', // 特殊的動作代碼
                'target_id' => $targetUser->id,
                'changes' => [
                    'role' => [
                        'from' => 'OM', // 原本是總督
                        'to' => $this->form['role'] // 試圖改成其他
                    ]
                ],
                // 寫入嚴厲的描述
                'description' => "警告：試圖移除 {$targetUser->name} 的運營總督權限 (已被系統安全攔截)",
                'ip_address' => request()->ip(),
            ]);
            // $this->addError('form.role', '運營總督的職位受系統保護，無法移除。');
            // [新增] B. 發送錯誤通知 (觸發前端紅色警告)
            $this->dispatch('operation-error', message: '權限駁回：運營總督受系統最高級別保護，禁止降級！');

            // 維持原本的表單錯誤提示
            $this->addError('form.role', '運營總督的職位受系統保護，無法移除。');
            return; // 強制中斷
        }

        // 3. 只有運營總督可以任命運營總督
        // 如果想把目標設為 OM，但操作者自己不是 OM，則禁止
        if ($this->form['role'] === User::ROLE_OM && !$currentUser->isOM()) {
            abort(403, '權限不足：只有運營總督可以任命其他總督。');
        }

        // -----------------------

        // 更新資料
        $targetUser->update($this->form);

        // --- [新增] 如果有任何變更，寫入 AuditLog ---
        if (!empty($changes)) {
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'UPDATE_USER', // 動作代號
                'target_id' => $targetUser->id,
                'changes' => $changes, // 這裡會自動存成 JSON
                'description' => "修改了玩家 {$targetUser->name} (#{$targetUser->id}) 的資料",
                'ip_address' => request()->ip(),
            ]);
        }

        $this->isModalOpen = false;
        // [新增] 發送成功事件通知前端
        $this->dispatch('operation-success', message: '人事派令用印完成，資料已更新！');
    }

    // 關閉視窗
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->editingUser = null;
    }

    public function render()
    {
        $users = User::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ])->layout('layouts.app'); // 暫時使用 app layout，建議之後建立 admin layout
    }
}
