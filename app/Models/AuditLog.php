<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'target_id',
        'changes',
        'description',
        'ip_address',
    ];

    protected $casts = [
        'changes' => 'array', // 自動將 JSON 轉為陣列
    ];

    // 關聯到操作者
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
