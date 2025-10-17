<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create(Restaurant $restaurant)
    {
        if (!$restaurant->is_active) {
            abort(404);
        }

        $categories = $restaurant->activeCategories()
            ->with('activeProducts')
            ->orderBy('sort_order')
            ->get();

        return view('orders.create', compact('restaurant', 'categories'));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        if (!$restaurant->is_active) {
            abort(404);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500',
            'delivery_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'customer_notes' => 'nullable|string|max:1000'
        ]);

        // Toplam tutarı hesapla
        $totalAmount = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            // Ürünün bu restorana ait olduğunu kontrol et
            if ($product->category->restaurant_id !== $restaurant->id) {
                return redirect()->back()->with('error', 'Invalid product selected.');
            }

            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $item['quantity'];

            $totalAmount += $totalPrice;

            $orderItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'notes' => $item['notes'] ?? null
            ];
        }

        // Siparişi oluştur
        $order = Order::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
            'phone' => $validated['phone'],
            'customer_notes' => $validated['customer_notes'] ?? null,
        ]);

        // Sipariş öğelerini ekle
        $order->items()->createMany($orderItems);

        return redirect()->route('orders.show', $order)
            ->with('success', 'تم تقديم طلبك بنجاح! سيتم التواصل معك قريباً.');
    }

    public function show(Order $order)
    {
        // Siparişin kullanıcıya ait olduğunu kontrol et
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['restaurant', 'items.product']);

        return view('orders.show', compact('order'));
    }

    public function index(User $user)
    {
        $orders = Auth::user()->orders()
            ->with('restaurant')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }
}
