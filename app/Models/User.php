<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_PLAYER = 'Player'; // 一般玩家
    const ROLE_GM = 'GM';         // 一般管理員 (處理檢舉、發放獎勵)
    const ROLE_OM = 'OM';         // 運營管理員 (修改系統參數、全

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gold',         // 金幣
        'stamina',      // 目前體力
        'max_stamina',  // 體力上限
        'job',          // 職業
        'avatar',       // 頭像路徑
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 快速判斷權限的方法
    public function isOM()
    {
        return $this->role === self::ROLE_OM;
    }
    public function isGM()
    {
        return $this->role === self::ROLE_GM || $this->isOM();
    }
    public function isPlayer()
    {
        return $this->role === self::ROLE_PLAYER;
    }
}
