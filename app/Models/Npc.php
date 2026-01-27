<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Npc extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'greeting',
        'avatar_id',
        'location',
        'is_active'
    ];

    // [新增] 設定型別轉換，確保取出來是 true/false 而不是 1/0
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 關聯頭像
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Avatar::class);
    }

    // 取得頭像網址 (如果沒設則回傳預設圖)
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? $this->avatar->path : '/images/npc-default.png';
    }
}
