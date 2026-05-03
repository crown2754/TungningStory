<?php

namespace App\Livewire\Admin;

use App\Models\Government;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GovernmentManagement extends Component
{
    public float $tax_rate;
    public int $public_order;
    public int $land_development;
    public int $treasury;
    public int $population;
    public int $military_count;
    public int $military_food;

    public function mount(): void
    {
        $gov = Government::current();
        $this->tax_rate         = (float) $gov->tax_rate;
        $this->public_order     = $gov->public_order;
        $this->land_development = $gov->land_development;
        $this->treasury         = $gov->treasury;
        $this->population       = $gov->population;
        $this->military_count   = $gov->military_count;
        $this->military_food    = $gov->military_food;
    }

    public function save(): void
    {
        $this->validate([
            'tax_rate'         => 'required|numeric|min:0|max:100',
            'public_order'     => 'required|integer|min:1|max:100',
            'land_development' => 'required|integer|min:1|max:100',
            'treasury'         => 'required|integer|min:0',
            'population'       => 'required|integer|min:0',
            'military_count'   => 'required|integer|min:0',
            'military_food'    => 'required|integer|min:0',
        ]);

        $gov = Government::current();

        // 記錄有哪些欄位被修改，寫入稽核日誌
        $changes = [];
        $fields = [
            'tax_rate'         => '稅率',
            'public_order'     => '治安指數',
            'land_development' => '開荒程度',
            'treasury'         => '政府資金',
            'population'       => '人口數',
            'military_count'   => '軍隊數量',
            'military_food'    => '軍糧數量',
        ];
        foreach ($fields as $field => $label) {
            if ($gov->$field != $this->$field) {
                $changes[] = "{$label}: {$gov->$field} → {$this->$field}";
            }
        }

        $gov->update([
            'tax_rate'         => $this->tax_rate,
            'public_order'     => $this->public_order,
            'land_development' => $this->land_development,
            'treasury'         => $this->treasury,
            'population'       => $this->population,
            'military_count'   => $this->military_count,
            'military_food'    => $this->military_food,
        ]);

        if (!empty($changes)) {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => '修改政府數據',
                'ip_address'  => request()->ip(),
                'description' => implode('；', $changes),
            ]);
        }

        $this->dispatch('operation-success', message: '承天府諭令已頒佈，史官已記錄在冊！');
    }

    public function render()
    {
        return view('livewire.admin.government-management')->layout('layouts.app');
    }
}
