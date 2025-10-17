<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Product;
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

        $categories = $restaurant->activeCategories()
            ->with(['activeProducts' => function($query) {
                $query->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('restaurants.show', compact('restaurant', 'categories'));
    }

    public function menu(Restaurant $restaurant)
    {
        if (!$restaurant->is_active) {
            abort(404);
        }

        $categories = $restaurant->activeCategories()
            ->with(['activeProducts' => function($query) {
                $query->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('restaurants.menu', compact('restaurant', 'categories'));
    }
}
