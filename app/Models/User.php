<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Orders ilişkisini ekleyin
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Aktif siparişler
    public function activeOrders()
    {
        return $this->hasMany(Order::class)
            ->whereNotIn('status', ['delivered', 'cancelled']);
    }

    // Son siparişler
    public function recentOrders($limit = 5)
    {
        return $this->hasMany(Order::class)
            ->latest()
            ->limit($limit);
    }
}
