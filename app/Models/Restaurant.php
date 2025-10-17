<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'opening_time',
        'closing_time',
        'address',
        'phone',
        'is_active'
    ];

    protected $casts = [
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function activeCategories()
    {
        return $this->hasMany(Category::class)->where('is_active', true);
    }

    // Products ilişkisini ekleyin
    public function products()
    {
        return $this->hasManyThrough(Product::class, Category::class);
    }

    // Aktif ürünler
    public function activeProducts()
    {
        return $this->hasManyThrough(Product::class, Category::class)
                    ->where('products.is_available', true)
                    ->where('categories.is_active', true);
    }

    // Orders ilişkisi
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getWorkingHoursAttribute()
    {
        return $this->opening_time->format('H:i') . ' - ' . $this->closing_time->format('H:i');
    }

    // İstatistikler için scope'lar
    public function scopeWithCounts($query)
    {
        return $query->withCount([
            'categories',
            'products',
            'activeProducts',
            'orders'
        ]);
    }
}
