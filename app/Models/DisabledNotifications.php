<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DisabledNotifications extends Model
{
    
    protected $fillable = [
        'name',
        'user_id'
    ];

    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }
}
