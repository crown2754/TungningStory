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

    // --- [修正 1] 定義角色常量 ---
    const ROLE_PLAYER = 'Player';
    const ROLE_GM = 'GM';
    const ROLE_OM = 'OM';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // --- [修正 2] 允許寫入遊戲數值與角色 ---
        'gold',
        'stamina',
        'max_stamina',
        'job',
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

    // --- [修正 3] 定義權限判斷方法 ---
    
    // 判斷是否為運營管理員
    public function isOM(): bool
    {
        return $this->role === self::ROLE_OM;
    }

    // 判斷是否為管理員 (包含 GM 與 OM)
    public function isGM(): bool
    {
        return in_array($this->role, [self::ROLE_GM, self::ROLE_OM]);
    }
}