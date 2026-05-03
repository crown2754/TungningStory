<?php

namespace App\Livewire\Admin;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class StockMonitor extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'name';
    public string $sortDir = 'asc';

    // 重置分頁（避免搜尋後頁面不對）
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        $items = Item::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate(15);

        // 計算全局的總量、警示數量
        $totalOfficialStock = Item::sum('stock');
        $totalCivilianStock = Item::sum('civilian_stock');
        $alertCount = Item::whereRaw('stock < target_stock * 0.2')->count(); // 官府庫存低於目標 20% 以下的告急品

        return view('livewire.admin.stock-monitor', [
            'items' => $items,
            'totalOfficialStock' => $totalOfficialStock,
            'totalCivilianStock' => $totalCivilianStock,
            'alertCount' => $alertCount,
        ])->layout('layouts.app');
    }
}
