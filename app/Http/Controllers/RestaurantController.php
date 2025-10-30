<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Product;
use Date;
use DateTime;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::where('is_active', true)->get();

        return view('restaurants.index', compact('restaurants'));
    }

    public function show(Restaurant $restaurant)
    {
        if (!$restaurant->is_active) {
            abort(404);
        }

        // Carbon ile zaman kontrolü
        $currentTime = now();
        $openingTime = \Carbon\Carbon::createFromTimeString($restaurant->opening_time);
        $closingTime = \Carbon\Carbon::createFromTimeString($restaurant->closing_time);

        // Restoranın açık olup olmadığını kontrol et
        $isOpen = $currentTime->between($openingTime, $closingTime);

        // Ayrıca kapanış saati açılış saatinden küçükse (gece yarısını geçen çalışma saatleri)
        // bu durumu da kontrol etmek için
        if ($closingTime->lessThan($openingTime)) {
            // Gece yarısını geçen çalışma saatleri için
            $isOpen = $currentTime->gte($openingTime) || $currentTime->lte($closingTime);
        }

        $categories = $restaurant->activeCategories()
            ->with(['activeProducts' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('restaurants.show', compact('restaurant', 'categories', 'isOpen'));
    }

    public function menu(Restaurant $restaurant)
    {
        if (!$restaurant->is_active) {
            abort(404);
        }

        $categories = $restaurant->activeCategories()
            ->with(['activeProducts' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('restaurants.menu', compact('restaurant', 'categories'));
    }
}
