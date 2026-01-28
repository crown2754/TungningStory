<?php

namespace App\Livewire\Admin;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class ItemManagement extends Component
{
    use WithPagination;

    // 表單欄位
    public $name, $description, $type = 'material';
    public $base_price = 100, $min_price = 10, $max_price = 1000;
    public $stock = 1000, $target_stock = 1000, $volatility = 0.5;

    public $editingItemId;
    public $showModal = false;
    public $previewPrice = 0; // 即時預覽價格

    // 驗證規則
    protected $rules = [
        'name' => 'required|string|max:20',
        'type' => 'required',
        'base_price' => 'required|integer|min:1',
        'stock' => 'required|integer|min:0',
        'target_stock' => 'required|integer|min:1',
        'volatility' => 'required|numeric|min:0|max:2',
    ];

    // 當任何數值改變時，重新計算預覽價格
    public function updated()
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

        // 核心公式：缺貨比例 = (目標 - 現有) / 目標
        $ratio = ($target - $current) / $target;

        // 價格 = 基價 * (1 + 波動率 * 缺貨比例)
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
        $this->base_price = $item->base_price;
        $this->min_price = $item->min_price;
        $this->max_price = $item->max_price;
        $this->stock = $item->stock;
        $this->target_stock = $item->target_stock;
        $this->volatility = $item->volatility;

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
            'base_price' => $this->base_price,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'stock' => $this->stock,
            'target_stock' => $this->target_stock,
            'volatility' => $this->volatility,
        ];

        if ($this->editingItemId) {
            Item::find($this->editingItemId)->update($data);
        } else {
            Item::create($data);
        }

        $this->showModal = false;
        $this->resetInput();
        $this->dispatch('operation-success', message: '物資數據已更新！市場價格將隨之波動。');
    }

    private function resetInput()
    {
        $this->name = '';
        $this->description = '';
        $this->type = 'material';
        $this->base_price = 100;
        $this->stock = 1000;
        $this->target_stock = 1000;
        $this->editingItemId = null;
    }

    public function render()
    {
        return view('livewire.admin.item-management', [
            'items' => Item::paginate(10)
        ])->layout('layouts.app');
    }
}
