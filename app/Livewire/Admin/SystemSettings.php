<?php

namespace App\Livewire\Admin;

use App\Models\GameSetting;
use App\Models\AuditLog;
use App\Models\SystemAsset;
use Livewire\Component;
use Livewire\WithFileUploads;

class SystemSettings extends Component
{
    use WithFileUploads;

    public $settings; // 用來綁定表單輸入 (key => value)

    // [新增] 圖片相關屬性
    public $newLogo;
    public $logoHistory = [];

    public function mount()
    {
        // 載入所有設定到陣列中
        $this->settings = GameSetting::all()->pluck('value', 'key')->toArray();
        $this->refreshLogoHistory(); // 載入歷史紀錄
    }

    public function refreshLogoHistory()
    {
        // 取出所有類型為 logo 的歷史圖片，最新的在前面
        $this->logoHistory = SystemAsset::where('type', 'logo')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // [新增] 上傳圖片方法
    public function uploadLogo()
    {
        $this->validate([
            'newLogo' => 'image|max:1024', // 1MB Max
        ]);

        // 1. 儲存檔案到 public/uploads/logos
        $path = $this->newLogo->store('uploads/logos', 'public');

        // 2. 寫入 SystemAsset 紀錄
        $asset = SystemAsset::create([
            'user_id' => auth()->id(),
            'type' => 'logo',
            'path' => '/storage/' . $path, // 轉成 Web 可存取路徑
            'original_name' => $this->newLogo->getClientOriginalName(),
            'mime_type' => $this->newLogo->getMimeType(),
        ]);

        // 3. 自動切換成新上傳的 Logo
        $this->selectLogo($asset->path);

        // 4. 清空上傳欄位並重整列表
        $this->newLogo = null;
        $this->refreshLogoHistory();

        $this->dispatch('operation-success', message: '新官印(Logo)已鑄造完成並啟用！');
    }

    // [新增] 選擇歷史圖片方法
    public function selectLogo($path)
    {
        // 更新 settings 陣列
        $this->settings['backend_logo_url'] = $path;

        // 更新資料庫
        GameSetting::where('key', 'backend_logo_url')->update(['value' => $path]);

        // 寫入 AuditLog
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_SETTING',
            'description' => "更換了後台標誌(Logo)",
            'changes' => ['new_path' => $path],
            'ip_address' => request()->ip(),
        ]);

        $this->dispatch('operation-success', message: '後台標誌已更換！');
    }

    public function update()
    {
        // 驗證輸入 (確保都是數字)
        $this->validate([
            'settings.initial_gold' => 'required|integer|min:0',
            'settings.initial_stamina' => 'required|integer|min:0',
            'settings.initial_max_stamina' => 'required|integer|min:1',
        ]);

        foreach ($this->settings as $key => $value) {
            $setting = GameSetting::where('key', $key)->first();

            if ($setting && $setting->value != $value) {
                // 記錄操作日誌
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'UPDATE_SETTING', // 動作代碼
                    'target_id' => $setting->id, // 目標 ID (設定項目的 ID)
                    'changes' => [
                        'key' => $key,
                        'name' => $setting->name,
                        'from' => $setting->value, // 舊值
                        'to' => $value,           // 新值
                    ],
                    'description' => "修訂了 [{$setting->name}]：由 {$setting->value} 改為 {$value}",
                    'ip_address' => request()->ip(),
                ]);

                $setting->update(['value' => $value]);
            }
        }

        $this->dispatch('operation-success', message: '府庫規章已修訂，並已登載於史冊！');
    }

    public function render()
    {
        // 傳遞完整的設定物件給前端
        $settingModels = GameSetting::all()->keyBy('key');

        return view('livewire.admin.system-settings', [
            'settingModels' => $settingModels
        ])->layout('layouts.app'); // [修正] 必須加上這行，指定使用 Breeze 的 Layout
    }
}
