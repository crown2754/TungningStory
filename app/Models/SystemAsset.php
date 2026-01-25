<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAsset extends Model
{
    protected $fillable = ['user_id', 'type', 'path', 'original_name', 'mime_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
