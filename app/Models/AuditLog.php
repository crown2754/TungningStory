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

    /**
     * 覆寫 JSON 編碼設定，強制讓中文不轉碼 (Unicode)
     * 必須配合 Laravel 父類別的簽章：$value, $flags = 0
     */
    protected function asJson($value, $flags = 0): string
    {
        // 將傳入的 flags 加上 JSON_UNESCAPED_UNICODE
        return json_encode($value, $flags | JSON_UNESCAPED_UNICODE);
    }
}
