<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $restaurants = Restaurant::withCount(['categories', 'products'])->get();
        return view('admin.dashboard', compact('restaurants'));
    }

    // Restoran Yönetimi
    public function restaurants()
    {
        $restaurants = Restaurant::all();
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function createRestaurant()
    {
        return view('admin.restaurants.create');
    }

    public function storeRestaurant(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('restaurants', 'public');
        }

        Restaurant::create($validated);

        return redirect()->route('admin.restaurants')->with('success', 'تم إضافة المطعم بنجاح');
    }

    public function editRestaurant(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function updateRestaurant(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('logo')) {

            $validated['logo'] = $request->file('logo')->store('restaurants', 'public');
        }

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants')->with('success', 'تم تحديث المطعم بنجاح');
    }
    public function deleteRestaurant(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('admin.dashboard')->with('success', value: 'تم حذف المطعم بنجاح');
    }

    // Kategori Yönetimi
    public function categories(Restaurant $restaurant)
    {
        $categories = $restaurant->categories()->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('restaurant', 'categories'));
    }

    public function createCategory(Restaurant $restaurant)
    {
        return view('admin.categories.create', compact('restaurant'));
    }

    public function storeCategory(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $restaurant->categories()->create($validated);

        return redirect()->route('admin.categories', $restaurant)->with('success', 'تم إضافة الفئة بنجاح');
    }

    // Ürün Yönetimi
    public function products(Category $category)
    {
        $products = $category->products()->get();
        return view('admin.products.index', compact('category', 'products'));
    }

    public function createProduct(Category $category)
    {
        return view('admin.products.create', compact('category'));
    }

    public function storeProduct(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'required|integer|min:1',
            'is_available' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $category->products()->create($validated);

        return redirect()->route('admin.products', $category)->with('success', 'تم إضافة المنتج بنجاح');
    }

    // AdminController içine ekleyin
    public function orders()
    {
        $orders = Order::with(['user', 'restaurant'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'restaurant', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
