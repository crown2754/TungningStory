<?php

namespace App\Livewire\Admin;

use App\Models\Item;
use App\Models\AuditLog; // [新增] 引入稽核模型
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // [新增] 引入檔案上傳

class ItemManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $description, $type = 'material', $unit = '個';
    public $base_price = 100, $min_price = 10, $max_price = 1000;
    public $stock = 1000, $target_stock = 1000, $volatility = 0.5;

    // [新增] 圖片相關屬性
    public $image; // 用於接收新上傳的檔案
    public $current_image_path; // 顯示目前的圖片

    public $editingItemId;
    public $showModal = false;
    public $previewPrice = 0;

    protected $rules = [
        'name' => 'required|string|max:20',
        'type' => 'required',
        'unit' => 'required|string|max:10',
        'base_price' => 'required|integer|min:1',
        'stock' => 'required|integer|min:0',
        'target_stock' => 'required|integer|min:1',
        'volatility' => 'required|numeric|min:0|max:2',
        'image' => 'nullable|image|max:1024', // [新增] 圖片驗證 (最大 1MB)
    ];

    // ... (updated 和 calculatePreviewPrice 方法保持不變) ...
    public function updated($propertyName)
    {
        $this->calculatePreviewPrice();
    }

    public function calculatePreviewPrice()
    {
        $base = (int) $this->base_price;
        $current = (int) $this->stock;
        $target = (int) $this->target_stock;
        $vol = (float) $this->volatility;

        if ($target <= 0) $target = 1;

        $ratio = ($target - $current) / $target;
        $finalPrice = $base * (1 + ($ratio * $vol));

        $this->previewPrice = max($this->min_price, min($this->max_price, (int)$finalPrice));
    }

    public function create()
    {
        $this->resetInput();
        $this->showModal = true;
        $this->calculatePreviewPrice();
    }

    public function edit($id)
    {
        $item = Item::find($id);
        $this->editingItemId = $id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->type = $item->type;
        $this->unit = $item->unit;
        $this->base_price = $item->base_price;
        $this->min_price = $item->min_price;
        $this->max_price = $item->max_price;
        $this->stock = $item->stock;
        $this->target_stock = $item->target_stock;
        $this->volatility = $item->volatility;
        $this->current_image_path = $item->image_path; // [新增] 載入目前圖片

        $this->showModal = true;
        $this->calculatePreviewPrice();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'unit' => $this->unit,
            'base_price' => $this->base_price,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'stock' => $this->stock,
            'target_stock' => $this->target_stock,
            'volatility' => $this->volatility,
        ];

        // [新增] 處理圖片上傳
        if ($this->image) {
            // 存到 public/images/items，檔名使用時間戳記防止快取
            $filename = time() . '_' . $this->image->getClientOriginalName();
            $this->image->storeAs('images/items', $filename, 'public_real'); // 需確保 filesystems.php 設定正確，或手動 move

            // 簡單起見，我們直接搬移到 public 目錄 (模擬 public_real disk)
            $this->image->storeAs('images/items', $filename, 'public_uploads');
            // 註：若沒有設定 public_uploads disk，可以改用 move
            // $this->image->move(public_path('images/items'), $filename);

            $data['image_path'] = 'images/items/' . $filename;
        }

        if ($this->editingItemId) {
            $item = Item::find($this->editingItemId);

            // [新增] 記錄詳細的修改項目 (Audit Log)
            $changes = [];
            if ($item->stock != $this->stock) $changes[] = "庫存: {$item->stock} -> {$this->stock}";
            if ($item->base_price != $this->base_price) $changes[] = "基價: {$item->base_price} -> {$this->base_price}";
            if ($this->image) $changes[] = "更新圖片";

            if (!empty($changes)) {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => '編輯物資',
                    'ip_address' => request()->ip(),
                    'details' => "修改 {$item->name}: " . implode(', ', $changes),
                ]);
            }

            $item->update($data);
        } else {
            $newItem = Item::create($data);

            // [新增] 建立紀錄
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => '新增物資',
                'ip_address' => request()->ip(),
                'details' => "新增物資: {$newItem->name}",
            ]);
        }

        $this->showModal = false;
        $this->resetInput();
        $this->dispatch('operation-success', message: '物資資料已更新！');
    }

    private function resetInput()
    {
        $this->name = '';
        $this->description = '';
        $this->type = 'material';
        $this->unit = '個';
        $this->base_price = 100;
        $this->stock = 1000;
        $this->target_stock = 1000;
        $this->editingItemId = null;
        $this->image = null; // 重置上傳
        $this->current_image_path = null;
    }

    public function render()
    {
        return view('livewire.admin.item-management', [
            'items' => Item::orderBy('id')->paginate(10)
        ])->layout('layouts.app');
    }
}
